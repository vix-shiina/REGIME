<?php

namespace App\Controllers;

use App\Traitements\TraitementRegime;

class Regime extends BaseController
{
    public function index()
    {
        $session = service('session');
        $userId = (int) $session->get('user_id');

        if (empty($userId)) {
            return redirect()->to('/SignIn');
        }

        $traitement = new TraitementRegime();
        $current = $traitement->getCurrentRegime($userId);

        if (empty($current)) {
            return redirect()->to('/regime/create');
        }

        $context = $traitement->getUserContext($userId);

        return view('Regime/Current', [
            'user' => $context['user'] ?? [],
            'currentRegime' => $current,
        ]);
    }

    public function downloadPdf()
    {
        $session = service('session');
        $userId = (int) $session->get('user_id');

        if (empty($userId)) {
            return redirect()->to('/SignIn');
        }

        $traitement = new TraitementRegime();
        $current = $traitement->getCurrentRegime($userId);

        if (empty($current)) {
            $session->setFlashdata('flash_error', 'Aucun régime actif à exporter.');
            return redirect()->to('/regime/create');
        }

        $context = $traitement->getUserContext($userId);
        $user = $context['user'] ?? [];

        $filename = preg_replace('/[^A-Za-z0-9_\-]+/', '_', (string) ($current['RegimeNom'] ?? 'mon_regime')) ?: 'mon_regime';
        $pdf = $this->buildSimplePdf($this->buildPdfLines($user, $current), (string) ($current['RegimeNom'] ?? 'Mon regime'));

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '.pdf"')
            ->setHeader('Content-Length', (string) strlen($pdf))
            ->setBody($pdf);
    }

    public function create()
    {
        $session = service('session');
        $userId = (int) $session->get('user_id');

        if (empty($userId)) {
            return redirect()->to('/SignIn');
        }

        $traitement = new TraitementRegime();
        $context = $traitement->getUserContext($userId);

        if (!$context) {
            return redirect()->to('/profil');
        }

        if (!empty($context['currentRegime'])) {
            $session->setFlashdata('flash_error', 'Vous avez déjà un régime actif.');
            return redirect()->to('/regime');
        }

        $user = $context['user'];
        $weight = isset($user['Poids']) ? (float) $user['Poids'] : null;
        $height = isset($user['Taille']) ? ((float) $user['Taille']) / 100 : null;

        $initialPreview = null;
        if (!empty($weight) && !empty($height)) {
            $initialPreview = $traitement->suggestPlan([
                'weight' => $weight,
                'height' => $height,
                'duration_months' => 1,
                'sport_id' => 0,
                'sport_frequency' => 0,
            ], $context['regimes'], $context['sports']);
        }

        return view('Regime/Create', [
            'user' => $user,
            'regimes' => $context['regimes'],
            'sports' => $context['sports'],
            'initialPreview' => $initialPreview,
            'currentRegime' => $context['currentRegime'] ?? null,
        ]);
    }

    public function store()
    {
        $session = service('session');
        $userId = (int) $session->get('user_id');

        if (empty($userId)) {
            return redirect()->to('/SignIn');
        }

        $traitement = new TraitementRegime();
        $context = $traitement->getUserContext($userId);

        if (!$context) {
            $session->setFlashdata('flash_error', 'Impossible de charger votre profil.');
            return redirect()->to('/regime/create');
        }

        if (!empty($context['currentRegime'])) {
            $session->setFlashdata('flash_error', 'Vous avez déjà un régime actif.');
            return redirect()->to('/regime');
        }

        $weight = $this->request->getPost('weight');
        $height = $this->request->getPost('height');
        $mode = $this->request->getPost('mode') ?? 'suggested';
        $targetUnit = $this->request->getPost('target_unit');
        $targetValue = $this->request->getPost('target_value');

        if (!is_numeric($weight) || (float) $weight <= 0 || !is_numeric($height) || (float) $height <= 0) {
            $session->setFlashdata('flash_error', 'Veuillez renseigner un poids et une taille valides.');
                return redirect()->to('/regime/create')->withInput();
        }

        if (!in_array($mode, ['suggested', 'custom'], true)) {
            $session->setFlashdata('flash_error', 'Veuillez choisir un mode de régime valide.');
                return redirect()->to('/regime/create')->withInput();
        }

        if ($mode === 'custom' && (!is_numeric($targetValue) || (float) $targetValue <= 0 || !in_array($targetUnit, ['bmi', 'weight'], true))) {
            $session->setFlashdata('flash_error', 'Veuillez renseigner un objectif personnalisé valide.');
                return redirect()->to('/regime/create')->withInput();
        }

        $payload = [
            'mode' => $mode,
            'weight' => $weight,
            'height' => $height,
            'duration_months' => $this->request->getPost('duration_months'),
            'payment_type' => $this->request->getPost('payment_type'),
            'selected_regime_id' => $this->request->getPost('selected_regime_id'),
            'selected_sport_id' => $this->request->getPost('sport_id'),
            'sport_id' => $this->request->getPost('sport_id'),
            'sport_frequency' => $this->request->getPost('sport_frequency'),
            'target_unit' => $targetUnit,
            'target_value' => $targetValue,
        ];

        $result = $traitement->createRegimeSelection($userId, $payload, $context['regimes'], $context['sports']);

        if (!empty($result['success'])) {
            $session->setFlashdata('flash_success', $result['message'] ?? 'Régime créé.');
            return redirect()->to('/regime');
        }

        $session->setFlashdata('flash_error', $result['message'] ?? 'Impossible de créer le régime.');
            return redirect()->to('/regime/create')->withInput();
    }

    private function buildPdfLines(array $user, array $currentRegime): array
    {
        $regimeName = (string) ($currentRegime['RegimeNom'] ?? 'Mon regime');
        $type = (string) ($currentRegime['TypeDeRegime'] ?? '-');
        $payment = (string) ($currentRegime['Paiement'] ?? '-');
        $dateDebut = (string) ($currentRegime['DateDebut'] ?? '-');
        $duree = (int) ($currentRegime['DureeEnJours'] ?? 0);
        $joursRestants = isset($currentRegime['remaining_days']) ? (int) $currentRegime['remaining_days'] : null;
        $prixJournalier = isset($currentRegime['PrixJournaliere']) ? (float) $currentRegime['PrixJournaliere'] : null;
        $efficacite = isset($currentRegime['EfficacitePoidsParSemaine']) ? (float) $currentRegime['EfficacitePoidsParSemaine'] : null;

        $lines = [
            'REGIME ACTIF',
            '------------------------------',
            'Nom du regime: ' . $regimeName,
            'Type: ' . $type,
            'Paiement: ' . $payment,
            'Date de debut: ' . $dateDebut,
            'Duree: ' . ($duree > 0 ? $duree . ' jours' : '-'),
            'Jours restants: ' . ($joursRestants !== null ? $joursRestants . ' jours' : '-'),
            'Prix journalier: ' . ($prixJournalier !== null ? number_format($prixJournalier, 0, ',', ' ') . ' Ar' : '-'),
            'Efficacite / semaine: ' . ($efficacite !== null ? number_format($efficacite, 2, ',', ' ') . ' kg/semaine' : '-'),
            '',
            'CLIENT',
            'Nom: ' . (string) ($user['Nom'] ?? '-'),
            'Prenom: ' . (string) ($user['Prenom'] ?? '-'),
            'Age: ' . (!empty($user['Age']) ? (string) $user['Age'] . ' ans' : '-'),
            'Genre: ' . (string) ($user['Genre'] ?? '-'),
        ];

        if (!empty($currentRegime['SportNom'])) {
            $lines = array_merge($lines, [
                '',
                'SPORT',
                'Nom: ' . (string) $currentRegime['SportNom'],
                'Type: ' . (string) ($currentRegime['TypeDeSport'] ?? '-'),
                'Efficacite / seance: ' . (isset($currentRegime['EfficacitePoidsParSceance']) ? number_format((float) $currentRegime['EfficacitePoidsParSceance'], 2, ',', ' ') . ' kg/seance' : '-'),
                'Duree: ' . (string) ($currentRegime['SportDureeEnJours'] ?? '-') . ' jours',
            ]);
        }

        return $lines;
    }

    private function buildSimplePdf(array $lines, string $title): string
    {
        $escape = static function (string $text): string {
            $converted = @iconv('UTF-8', 'Windows-1252//TRANSLIT', $text);
            if ($converted === false) {
                $converted = utf8_decode($text);
            }

            return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $converted);
        };

        $content = "BT\n/F1 12 Tf\n";
        $x = 50;
        $y = 790;
        foreach ($lines as $line) {
            $content .= sprintf("1 0 0 1 %d %d Tm (%s) Tj\n", $x, $y, $escape((string) $line));
            $y -= 16;
            if ($y < 40) {
                break;
            }
        }
        $content .= "ET\n";

        $objects = [
            1 => "<< /Type /Catalog /Pages 2 0 R >>",
            2 => "<< /Type /Pages /Kids [3 0 R] /Count 1 >>",
            3 => "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>",
            4 => "<< /Type /Font /Subtype /Type1 /BaseFont /Courier >>",
            5 => "<< /Length " . strlen($content) . " >>\nstream\n" . $content . "endstream",
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [0];

        foreach ($objects as $number => $object) {
            $offsets[$number] = strlen($pdf);
            $pdf .= $number . " 0 obj\n" . $object . "\nendobj\n";
        }

        $xref = strlen($pdf);
        $pdf .= "xref\n0 6\n";
        $pdf .= sprintf("%010d %05d f \n", 0, 65535);
        for ($i = 1; $i <= 5; $i++) {
            $pdf .= sprintf("%010d %05d n \n", $offsets[$i], 0);
        }
        $pdf .= "trailer\n<< /Size 6 /Root 1 0 R /Info << /Title (" . $escape($title) . ") >> >>\n";
        $pdf .= "startxref\n" . $xref . "\n%%EOF";

        return $pdf;
    }
}