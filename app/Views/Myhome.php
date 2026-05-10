<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Myhome</title>

    <link rel="stylesheet" href="/assets/css/myhome.css">
</head>

<body class="has-promo-header">

<?php echo view('partials/Header'); ?>

<?php

$currentRegime = $currentRegime ?? [];

$regimeName = '-';
$remainingDisplay = '-';
$nextDateDisplay = 'N/A';

$isActive = false;

if (!empty($currentRegime) && is_array($currentRegime)) {

    $regimeName = (string) ($currentRegime['RegimeNom'] ?? '-');

    $isActive = !empty($currentRegime['is_active']);

    if (isset($currentRegime['remaining_days'])) {

        $remainingDisplay =
            (int) $currentRegime['remaining_days'] . ' jour(s) restant(s)';
    }

    $startRaw = (string) ($currentRegime['DateDebut'] ?? '');

    $durationDays = (int) ($currentRegime['DureeEnJours'] ?? 0);

    if ($startRaw !== '' && $durationDays > 0) {

        try {

            $startDate = new DateTimeImmutable($startRaw);

            $endDate = $startDate->modify('+' . $durationDays . ' days');

            $nextDateDisplay = $endDate->format('d/m/Y');

        } catch (\Throwable $e) {

            $nextDateDisplay = 'N/A';
        }
    }
}

?>

<main class="myhome container">

    <header class="welcome">

        <div>

            <p class="welcome-kicker">
                Tableau de bord
            </p>

            <h1>
                Bonjour
                <?php echo htmlspecialchars($userName ?? 'Utilisateur'); ?>
            </h1>

            <p class="sub">
                Résumé rapide de votre compte et de votre régime actuel.
            </p>

        </div>

        <div class="welcome-status">

            <span class="status-pill <?php echo $isActive ? 'active' : 'inactive'; ?>">

                <?php echo $isActive ? 'Régime actif' : 'Aucun régime actif'; ?>

            </span>

        </div>

    </header>

    <section class="summary-grid">

        <article class="card">

            <div class="card-head">
                <h3>Solde</h3>
            </div>

            <p class="value">
                <?php echo number_format($userSolde ?? 0, 0, ',', ' ') . ' Ar'; ?>
            </p>

            <small class="muted">
                Solde disponible sur votre compte
            </small>

        </article>

        <article class="card">

            <div class="card-head">
                <h3>Régime actuel</h3>
            </div>

            <p class="value">
                <?php echo htmlspecialchars($regimeName); ?>
            </p>

            <small class="muted">
                Programme actuellement attribué
            </small>

        </article>

        <article class="card">

            <div class="card-head">
                <h3>Fin estimée</h3>
            </div>

            <p class="value">
                <?php echo htmlspecialchars($nextDateDisplay); ?>
            </p>

            <small class="muted">
                <?php echo htmlspecialchars($remainingDisplay); ?>
            </small>

        </article>

    </section>

    <section class="big-actions">

        <a class="btn primary" href="/profil">
            Mon profil
        </a>

        <a class="btn primary" href="/regime">
            Mon régime
        </a>

        <a class="btn secondary" href="/dashboard">
            Dashboard
        </a>

    </section>

    <section class="quick-actions">

        <div class="section-title">

            <h2>
                Actions rapides
            </h2>

        </div>

        <div class="actions">

            <a href="/profil/edit" class="qa">
                Modifier le profil
            </a>

            <a href="/regime" class="qa">
                Voir le régime
            </a>

            <a href="/contact" class="qa">
                Contacter le support
            </a>

        </div>

    </section>

    <aside class="tip">

        <strong>
            Astuce :
        </strong>

        <?php

        echo !empty($tip)
            ? htmlspecialchars($tip)
            : 'Consultez régulièrement votre progression pour suivre votre évolution.';

        ?>

    </aside>

</main>

<?php echo view('partials/Footer'); ?>

</body>
</html>