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
            return redirect()->to('/regime/create');
        }

        if (!in_array($mode, ['suggested', 'custom'], true)) {
            $session->setFlashdata('flash_error', 'Veuillez choisir un mode de régime valide.');
            return redirect()->to('/regime/create');
        }

        if ($mode === 'custom' && (!is_numeric($targetValue) || (float) $targetValue <= 0 || !in_array($targetUnit, ['bmi', 'weight'], true))) {
            $session->setFlashdata('flash_error', 'Veuillez renseigner un objectif personnalisé valide.');
            return redirect()->to('/regime/create');
        }

        $payload = [
            'mode' => $mode,
            'weight' => $weight,
            'height' => $height,
            'duration_months' => $this->request->getPost('duration_months'),
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
        return redirect()->to('/regime/create');
    }
}