<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Mon régime</title>

    <link rel="stylesheet" href="/assets/css/current.css">
</head>
<body>

<?php echo view('partials/Header'); ?>
<?php echo view('partials/Flash'); ?>

<?php

$currentRegime = $currentRegime ?? [];
$user = $user ?? [];

$regimeName = (string) ($currentRegime['RegimeNom'] ?? 'Mon régime');
$type = (string) ($currentRegime['TypeDeRegime'] ?? '-');
$payment = (string) ($currentRegime['Paiement'] ?? '-');

$startRaw = (string) ($currentRegime['DateDebut'] ?? '');

$durationDays = (int) ($currentRegime['DureeEnJours'] ?? 0);

$remainingDays = isset($currentRegime['remaining_days'])
    ? (int) $currentRegime['remaining_days']
    : null;

$isActive = !empty($currentRegime['is_active']);

$priceDailyValue = isset($currentRegime['PrixJournaliere'])
    ? (float) $currentRegime['PrixJournaliere']
    : null;

$efficacyWeekly = isset($currentRegime['EfficacitePoidsParSemaine'])
    ? (float) $currentRegime['EfficacitePoidsParSemaine']
    : null;

$startDisplay = '-';
$endDisplay = '-';
$elapsedDays = null;

if ($startRaw !== '') {

    try {

        $startDate = new \DateTimeImmutable($startRaw);

        $startDisplay = $startDate->format('d/m/Y');

        if ($durationDays > 0) {

            $endDate = $startDate->modify('+' . $durationDays . ' days');

            $endDisplay = $endDate->format('d/m/Y');
        }

    } catch (\Throwable $e) {

        $startDisplay = $startRaw;
    }
}

if ($remainingDays !== null && $durationDays > 0) {

    $elapsedDays = max(0, $durationDays - $remainingDays);
}

$progressPct = 0;

if ($elapsedDays !== null && $durationDays > 0) {

    $progressPct = (int) round(($elapsedDays / $durationDays) * 100);

    $progressPct = min(100, max(0, $progressPct));
}

$priceDailyDisplay = $priceDailyValue !== null
    ? number_format($priceDailyValue, 0, ',', ' ') . ' Ar'
    : '-';

$estimatedTotal = ($priceDailyValue !== null && $durationDays > 0)
    ? number_format($priceDailyValue * $durationDays, 0, ',', ' ') . ' Ar'
    : '-';

?>

<main class="regime-current-page">

    <section class="regime-top-card">

        <div class="regime-top-header">

            <div>

                <p class="regime-kicker">
                    Régime actif
                </p>

                <h1 class="regime-title">
                    <?php echo htmlspecialchars($regimeName); ?>
                </h1>

                <p class="regime-subtitle">
                    Votre programme est actuellement en cours.
                </p>

            </div>

            <div class="regime-status-group">

                <span class="regime-badge">
                    <?php echo $isActive ? 'Actif' : 'Inactif'; ?>
                </span>

                <span class="regime-badge soft">
                    <?php echo htmlspecialchars($type); ?>
                </span>

                <span class="regime-badge soft">
                    <?php echo htmlspecialchars($payment); ?>
                </span>

            </div>

        </div>

        <div class="regime-progress-section">

            <div class="regime-progress-top">

                <span>Progression</span>

                <strong>
                    <?php echo $progressPct; ?>%
                </strong>

            </div>

            <div class="regime-progress-bar">

                <span style="width: <?php echo $progressPct; ?>%"></span>

            </div>

        </div>

    </section>

    <section class="regime-grid">

        <article class="regime-card">

            <h2>
                Identité
            </h2>

            <div class="regime-row">
                <span>Nom</span>
                <strong><?php echo htmlspecialchars((string) ($user['Nom'] ?? '-')); ?></strong>
            </div>

            <div class="regime-row">
                <span>Prénom</span>
                <strong><?php echo htmlspecialchars((string) ($user['Prenom'] ?? '-')); ?></strong>
            </div>

            <div class="regime-row">
                <span>Âge</span>
                <strong>
                    <?php echo !empty($user['Age']) ? htmlspecialchars((string) $user['Age']) . ' ans' : '-'; ?>
                </strong>
            </div>

            <div class="regime-row">
                <span>Genre</span>
                <strong><?php echo htmlspecialchars((string) ($user['Genre'] ?? '-')); ?></strong>
            </div>

        </article>

        <article class="regime-card">

            <h2>
                Informations générales
            </h2>

            <div class="regime-row">
                <span>Date début</span>
                <strong><?php echo htmlspecialchars($startDisplay); ?></strong>
            </div>

            <div class="regime-row">
                <span>Date fin</span>
                <strong><?php echo htmlspecialchars($endDisplay); ?></strong>
            </div>

            <div class="regime-row">
                <span>Durée</span>
                <strong>
                    <?php echo $durationDays > 0 ? $durationDays . ' jours' : '-'; ?>
                </strong>
            </div>

            <div class="regime-row">
                <span>Jours restants</span>
                <strong>
                    <?php echo $remainingDays !== null ? $remainingDays . ' jours' : '-'; ?>
                </strong>
            </div>

        </article>

        <article class="regime-card">

            <h2>
                Paiement
            </h2>

            <div class="regime-row">
                <span>Prix journalier</span>
                <strong><?php echo htmlspecialchars($priceDailyDisplay); ?></strong>
            </div>

            <div class="regime-row">
                <span>Coût estimé</span>
                <strong><?php echo htmlspecialchars($estimatedTotal); ?></strong>
            </div>

            <div class="regime-row">
                <span>Paiement</span>
                <strong><?php echo htmlspecialchars($payment); ?></strong>
            </div>

        </article>

        <article class="regime-card">

            <h2>
                Détails du régime
            </h2>

            <div class="regime-row">
                <span>Nom</span>
                <strong><?php echo htmlspecialchars($regimeName); ?></strong>
            </div>

            <div class="regime-row">
                <span>Type</span>
                <strong><?php echo htmlspecialchars($type); ?></strong>
            </div>

            <div class="regime-row">
                <span>Efficacité / semaine</span>
                <strong>
                    <?php echo $efficacyWeekly !== null ? htmlspecialchars((string) $efficacyWeekly) : '-'; ?>
                </strong>
            </div>

        </article>

        <article class="regime-card">

            <h2>
                Objectifs de poids
            </h2>

            <div class="regime-row">
                <span>Poids de départ</span>
                <strong>
                    <?php echo !empty($currentRegime['PoidsDepart']) 
                        ? htmlspecialchars((string) $currentRegime['PoidsDepart']) . ' kg'
                        : (!empty($currentRegime['PoidsActuel']) ? htmlspecialchars((string) $currentRegime['PoidsActuel']) . ' kg' : '-'); ?>
                </strong>
            </div>

            <div class="regime-row">
                <span>Poids théorique perdu à partir de la cure</span>
                <strong>
                    <?php 
                        if (!empty($currentRegime['EfficacitePoidsParSemaine']) && !empty($durationDays)) {
                            $semaines = $durationDays / 7;
                            $poidsPerdu = (float) $currentRegime['EfficacitePoidsParSemaine'] * $semaines;
                            echo htmlspecialchars((string) round($poidsPerdu, 2)) . ' kg';
                        } else {
                            echo '-';
                        }
                    ?>
                </strong>
            </div>

        </article>

        <article class="regime-card">

            <h2>
                Sport
            </h2>

            <?php if (!empty($currentRegime['SportNom'])): ?>
                <div class="regime-row">
                    <span>Nom du sport</span>
                    <strong>
                        <?php echo htmlspecialchars((string) $currentRegime['SportNom']); ?>
                    </strong>
                </div>

                <div class="regime-row">
                    <span>Type de sport</span>
                    <strong>
                        <?php echo htmlspecialchars((string) ($currentRegime['TypeDeSport'] ?? '-')); ?>
                    </strong>
                </div>

                <div class="regime-row">
                    <span>Efficacité (poids/séance)</span>
                    <strong>
                        <?php echo isset($currentRegime['EfficacitePoidsParSceance']) 
                            ? htmlspecialchars((string) $currentRegime['EfficacitePoidsParSceance']) 
                            : '-'; ?>
                    </strong>
                </div>

                <div class="regime-row">
                    <span>Durée</span>
                    <strong>
                        <?php echo htmlspecialchars((string) ($currentRegime['SportDureeEnJours'] ?? '-')) . ' jours'; ?>
                    </strong>
                </div>
            <?php else: ?>
                <p class="muted">Aucun sport sélectionné.</p>
            <?php endif; ?>

        </article>

    </section>

    <section class="regime-actions">

        <button class="regime-btn primary" type="button" onclick="window.print()">
            Imprimer en PDF
        </button>

        <a class="regime-btn secondary" href="/profil">
            Retour profil
        </a>

    </section>

</main>

<?php echo view('partials/Footer'); ?>

<script>
(function () {
    const title = <?php echo json_encode($regimeName . ' - Mon régime', JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
    window.addEventListener('beforeprint', () => {
        document.title = title;
    });
})();
</script>

</body>
</html>