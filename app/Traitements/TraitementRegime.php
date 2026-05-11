<?php

namespace App\Traitements;

class TraitementRegime
{
    protected \PDO $pdo;

    public function __construct()
    {
        $dsn = 'mysql:host=127.0.0.1;dbname=REGIME;charset=utf8mb4';
        $this->pdo = new \PDO($dsn, 'root', '', [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    }

    public function getUserContext(int $userId): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT u.Id, u.Nom, u.Prenom, ui.Age, ui.Taille, ui.Poids
             FROM USER u
             LEFT JOIN UserInfo ui ON ui.UserId = u.Id
             WHERE u.Id = ?
             LIMIT 1'
        );
        $stmt->execute([$userId]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$user) {
            return null;
        }

        return [
            'user' => $user,
            'currentRegime' => $this->getCurrentRegime($userId),
            'regimes' => $this->getRegimes(),
            'sports' => $this->getSports(),
        ];
    }

    public function getCurrentRegime(int $userId): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ru.Id AS RegimeUserId,
                    ru.RegimeId,
                    ru.DateDebut,
                    ru.DureeEnJours,
                    ru.Paiement,
                    r.RegimeNom,
                    r.PrixJournaliere,
                    r.EfficacitePoidsParSemaine,
                    tdr.TypeDeRegime,
                    su.Id AS SportUserId,
                    su.SportId,
                    su.DureeEnJours AS SportDureeEnJours,
                    s.SportNom,
                    s.EfficacitePoidsParSceance,
                    ts.TypeDeSport,
                    ui.Poids AS PoidsActuel,
                    (SELECT e.Poids FROM Evolution e WHERE e.UserId = ru.UserId ORDER BY e.DateEvolution ASC LIMIT 1) AS PoidsDepart
             FROM RegimeUser ru
             LEFT JOIN REGIME r ON r.Id = ru.RegimeId
             LEFT JOIN TypeDeRegime tdr ON tdr.Id = r.TypeDeRegimeId
             LEFT JOIN SportUser su ON su.UserId = ru.UserId AND su.DateDebut = ru.DateDebut
             LEFT JOIN SPORT s ON s.Id = su.SportId
             LEFT JOIN TypeDeSport ts ON ts.Id = s.TypeDeSportId
             LEFT JOIN UserInfo ui ON ui.UserId = ru.UserId
             WHERE ru.UserId = ?
             ORDER BY ru.DateDebut DESC
             LIMIT 1'
        );
        $stmt->execute([$userId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        try {
            $start = new \DateTimeImmutable($row['DateDebut']);
            $duration = max(0, (int) ($row['DureeEnJours'] ?? 0));
            $end = $start->modify('+'.$duration.' days');
            $today = new \DateTimeImmutable('today');
            $remaining = $today > $end ? 0 : (int) $today->diff($end)->format('%a');
            $row['remaining_days'] = $remaining;
            $row['is_active'] = $remaining > 0;
        } catch (\Throwable $e) {
            $row['remaining_days'] = null;
            $row['is_active'] = false;
        }

        return !empty($row['is_active']) ? $row : null;
    }

    public function getRegimes(): array
    {
        $stmt = $this->pdo->query(
            'SELECT r.Id,
                    r.RegimeNom,
                    r.PrixJournaliere,
                    r.EfficacitePoidsParSemaine,
                    r.TypeDeRegimeId,
                    tdr.TypeDeRegime,
                    COALESCE(cr.Viande, 0) AS Viande,
                    COALESCE(cr.Poisson, 0) AS Poisson,
                    COALESCE(cr.Volailles, 0) AS Volailles
             FROM REGIME r
             LEFT JOIN TypeDeRegime tdr ON tdr.Id = r.TypeDeRegimeId
             LEFT JOIN CompoRegime cr ON cr.RegimeId = r.Id
             ORDER BY tdr.TypeDeRegime ASC, r.RegimeNom ASC'
        );

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getSports(): array
    {
        $stmt = $this->pdo->query(
            'SELECT s.Id, s.SportNom, s.EfficacitePoidsParSceance, s.TypeDeSportId, tds.TypeDeSport
             FROM SPORT s
             LEFT JOIN TypeDeSport tds ON tds.Id = s.TypeDeSportId
             ORDER BY s.SportNom ASC'
        );

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function upsertPhysicalInfo(int $userId, ?float $weight, ?float $height): void
    {
        $stmt = $this->pdo->prepare('SELECT Id FROM UserInfo WHERE UserId = ? LIMIT 1');
        $stmt->execute([$userId]);
        $userInfoId = $stmt->fetchColumn();

        if ($userInfoId) {
            $update = $this->pdo->prepare(
                'UPDATE UserInfo SET Poids = COALESCE(?, Poids), Taille = COALESCE(?, Taille) WHERE UserId = ?'
            );
            $update->execute([$weight, $height, $userId]);
            return;
        }

        $insert = $this->pdo->prepare('INSERT INTO UserInfo (UserId, Poids, Taille) VALUES (?, ?, ?)');
        $insert->execute([$userId, $weight, $height]);
    }

    public function computeImc(?float $weight, ?float $height): ?float
    {
        if (empty($weight) || empty($height) || $height <= 0) {
            return null;
        }

        return round($weight / ($height * $height), 2);
    }

    public function suggestPlan(array $payload, array $regimes, array $sports): array
    {
        $weight = isset($payload['weight']) ? (float) $payload['weight'] : null;
        $height = isset($payload['height']) ? (float) $payload['height'] : null;
        $durationMonths = isset($payload['duration_months']) && (float) $payload['duration_months'] > 0
            ? (float) $payload['duration_months']
            : 0.0;
        $sportId = !empty($payload['sport_id']) ? (int) $payload['sport_id'] : 0;
        $frequency = !empty($payload['sport_frequency']) ? max(0, (int) $payload['sport_frequency']) : 0;

        $currentImc = $this->computeImc($weight, $height);
        if ($currentImc === null) {
            return [
                'success' => false,
                'message' => 'Veuillez renseigner le poids et la taille.',
            ];
        }

        $currentZone = $this->resolveBmiZone($currentImc);
        $targetBmi = $this->resolveTargetBmiForZone($currentZone);
        $targetWeight = round($targetBmi * ($height * $height), 2);
        $requiredDelta = round($targetWeight - $weight, 2);

        // Déterminer la zone requise en fonction du delta
        // Si requiredDelta < 0, on a besoin d'une perte
        // Si requiredDelta > 0, on a besoin d'une prise
        $zone = ($requiredDelta < -0.1) ? 'perte' : (($requiredDelta > 0.1) ? 'prise' : 'maintien');

        $sportBonus = $this->resolveSportBonus($sportId, $frequency, $sports);
        $estimatedWeeks = $durationMonths > 0 ? round($durationMonths * 4.33, 2) : 0.0;

        $best = $this->rankRegimes($regimes, $requiredDelta, $estimatedWeeks, $sportBonus, $zone);

        $recommendedWeeks = $estimatedWeeks;
        if ($recommendedWeeks <= 0 && !empty($best)) {
            $weeklyImpact = max(0.1, (float) $best['EfficacitePoidsParSemaine'] + $sportBonus);
            $recommendedWeeks = max(1, (int) ceil(abs($requiredDelta) / $weeklyImpact));
        }

        return [
            'success' => true,
            'current_imc' => $currentImc,
            'zone' => $zone,
            'target_imc' => $targetBmi,
            'target_weight' => $targetWeight,
            'required_delta' => $requiredDelta,
            'estimated_weeks' => $estimatedWeeks,
            'recommended_weeks' => $recommendedWeeks,
            'sport_bonus' => $sportBonus,
            'best_regime' => $best,
        ];
    }

    public function createRegimeSelection(int $userId, array $payload, array $regimes, array $sports): array
    {
        $weight = isset($payload['weight']) ? (float) $payload['weight'] : null;
        $height = isset($payload['height']) ? (float) $payload['height'] : null;
        $durationMonths = isset($payload['duration_months']) && (float) $payload['duration_months'] > 0
            ? (float) $payload['duration_months']
            : 0.0;
        $sportId = !empty($payload['sport_id']) ? (int) $payload['sport_id'] : 0;
        $selectedSportId = !empty($payload['selected_sport_id']) ? (int) $payload['selected_sport_id'] : $sportId;
        $selectedRegimeId = !empty($payload['selected_regime_id']) ? (int) $payload['selected_regime_id'] : 0;
        $frequency = !empty($payload['sport_frequency']) ? max(0, (int) $payload['sport_frequency']) : 0;
        $mode = in_array(($payload['mode'] ?? 'suggested'), ['suggested', 'custom'], true) ? $payload['mode'] : 'suggested';

        $imc = $this->computeImc($weight, $height);
        if ($imc === null) {
            return [
                'success' => false,
                'message' => 'Veuillez renseigner le poids et la taille.',
            ];
        }

        if ($mode === 'custom') {
            $targetUnit = $payload['target_unit'] ?? 'bmi';
            $targetValue = isset($payload['target_value']) ? (float) $payload['target_value'] : 0.0;

            if ($targetValue <= 0) {
                return [
                    'success' => false,
                    'message' => 'Veuillez renseigner un objectif valide.',
                ];
            }

            $targetBmi = $targetUnit === 'weight' ? round($targetValue / ($height * $height), 2) : $targetValue;
            $targetWeight = round($targetBmi * ($height * $height), 2);
            $requiredDelta = round($targetWeight - $weight, 2);
            $sportBonus = $this->resolveSportBonus($sportId, $frequency, $sports);
            $estimatedWeeks = $durationMonths > 0 ? round($durationMonths * 4.33, 2) : 0.0;
            $zone = $targetBmi > $imc ? 'prise' : ($targetBmi < $imc ? 'perte' : 'maintien');

            $best = $selectedRegimeId > 0
                ? $this->findRegimeById($regimes, $selectedRegimeId)
                : null;

            if (empty($best)) {
                $best = $this->rankRegimes($regimes, $requiredDelta, $estimatedWeeks, $sportBonus, $zone);
            }

            if (empty($best)) {
                return [
                    'success' => false,
                    'message' => 'Aucun régime compatible trouvé.',
                ];
            }

            $recommendedWeeks = $estimatedWeeks;
            if ($recommendedWeeks <= 0) {
                $weeklyImpact = max(0.1, (float) $best['EfficacitePoidsParSemaine'] + $sportBonus);
                $recommendedWeeks = max(1, (int) ceil(abs($requiredDelta) / $weeklyImpact));
            }

            $preview = [
                'success' => true,
                'current_imc' => $imc,
                'zone' => $zone,
                'target_imc' => $targetBmi,
                'target_weight' => $targetWeight,
                'required_delta' => $requiredDelta,
                'estimated_weeks' => $estimatedWeeks,
                'recommended_weeks' => $recommendedWeeks,
                'sport_bonus' => $sportBonus,
                'best_regime' => $best,
                'mode' => 'custom',
                'target_bmi' => $targetBmi,
                'target_value' => $targetValue,
                'target_unit' => $targetUnit,
            ];

            return $this->storeSelection($userId, $preview, $weight, $height, $durationMonths, $mode, $selectedSportId, $frequency);
        }

        $preview = $this->suggestPlan([
            'weight' => $weight,
            'height' => $height,
            'duration_months' => $durationMonths,
            'sport_id' => $sportId,
            'sport_frequency' => $frequency,
        ], $regimes, $sports);

        if (empty($preview['success'])) {
            return $preview;
        }

        return $this->storeSelection($userId, $preview, $weight, $height, $durationMonths, $mode, $selectedSportId, $frequency);
    }

    private function storeSelection(int $userId, array $preview, ?float $weight, ?float $height, float $durationMonths, string $mode, int $sportId = 0, int $frequency = 0): array
    {
        $best = $preview['best_regime'] ?? null;
        if (empty($best)) {
            return [
                'success' => false,
                'message' => 'Aucun régime compatible trouvé.',
            ];
        }

        $durationDays = max(1, (int) round(($durationMonths > 0 ? $durationMonths : max(1, (float) ($preview['recommended_weeks'] ?? 4) / 4.33)) * 30));

        try {
            $this->pdo->beginTransaction();
            $this->upsertPhysicalInfo($userId, $weight, $height);

            $insert = $this->pdo->prepare(
                'INSERT INTO RegimeUser (UserId, RegimeId, DateDebut, DureeEnJours, Paiement) VALUES (?, ?, CURDATE(), ?, ?)'
            );
            $insert->execute([
                $userId,
                (int) $best['Id'],
                $durationDays,
                $durationDays > 30 ? 'Abonnement' : 'Paiement unique',
            ]);

            if ($sportId > 0) {
                $sportInsert = $this->pdo->prepare(
                    'INSERT INTO SportUser (UserId, SportId, DateDebut, DureeEnJours) VALUES (?, ?, CURDATE(), ?)'
                );
                $sportInsert->execute([
                    $userId,
                    $sportId,
                    max(1, $durationDays),
                ]);
            }

            $this->pdo->commit();

            return [
                'success' => true,
                'message' => $mode === 'custom' ? 'Régime personnalisé créé avec succès.' : 'Régime suggéré appliqué avec succès.',
                'selected_regime' => $best,
            ];
        } catch (\Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            return [
                'success' => false,
                'message' => 'Impossible de créer le régime.',
            ];
        }
    }

    private function rankRegimes(array $regimes, float $requiredDelta, float $estimatedWeeks, float $sportBonus, string $zone): ?array
    {
        $best = null;
        $bestScore = -PHP_INT_MAX;

        foreach ($regimes as $regime) {
            $type = strtolower((string) ($regime['TypeDeRegime'] ?? ''));
            
            // Déterminer la direction du régime
            $isPerte = str_contains($type, 'perte');
            $isPrise = str_contains($type, 'prise');
            
            // Direction du delta requis
            $needsLoss = $requiredDelta < 0;
            $needsGain = $requiredDelta > 0;
            
            // Vérifier la correspondance de direction - c'est un filtre strict
            if ($needsLoss && !$isPerte) {
                continue; // Skip, ce régime n'est pas adapté
            }
            if ($needsGain && !$isPrise) {
                continue; // Skip, ce régime n'est pas adapté
            }
            
            // Calculer l'efficacité réelle
            $efficacy = max(0.1, (float) ($regime['EfficacitePoidsParSemaine'] ?? 0));
            $weeks = $estimatedWeeks > 0 ? $estimatedWeeks : 4.0;
            
            // Direction du delta prédit (négatif pour perte, positif pour prise)
            $direction = $isPerte ? -1 : 1;
            $predictedDelta = round($direction * $efficacy * $weeks, 2);
            
            // Scorer: plus proche du delta requis = meilleur score
            // Aussi pénaliser les surestimations
            $difference = abs($predictedDelta - $requiredDelta);
            $score = 1000 - ($difference * 150); // Pénalité plus forte pour les écarts
            
            // Bonus supplémentaire pour correspondance exacte de zone
            if ($zone === 'perte' && $isPerte) {
                $score += 250;
            } elseif ($zone === 'prise' && $isPrise) {
                $score += 250;
            } elseif ($zone === 'maintien') {
                // Pour maintien, favoriser les petits mouvements
                $score += 200 - (abs($predictedDelta) * 50);
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $regime;
                $best['predicted_delta'] = $predictedDelta;
                $best['score'] = $score;
            }
        }

        return $best;
    }

    private function findRegimeById(array $regimes, int $id): ?array
    {
        foreach ($regimes as $regime) {
            if ((int) ($regime['Id'] ?? 0) === $id) {
                return $regime;
            }
        }

        return null;
    }

    private function resolveBmiZone(float $imc): string
    {
        if ($imc < 18.5) {
            return 'prise';
        }

        if ($imc > 24.9) {
            return 'perte';
        }

        return 'maintien';
    }

    private function resolveTargetBmiForZone(string $zone): float
    {
        return match ($zone) {
            'prise' => 21.0,
            'perte' => 22.5,
            default => 22.0,
        };
    }

    private function resolveSportBonus(int $sportId, int $frequency, array $sports): float
    {
        if ($sportId <= 0 || $frequency <= 0) {
            return 0.0;
        }

        foreach ($sports as $sport) {
            if ((int) ($sport['Id'] ?? 0) === $sportId) {
                return round(((float) ($sport['EfficacitePoidsParSceance'] ?? 0)) * $frequency * 0.15, 2);
            }
        }

        return 0.0;
    }
}