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

<main class="myhome container">
    <header class="welcome">
        <h1>Bonjour <?php echo htmlspecialchars($userName ?? 'Utilisateur'); ?></h1>
        <p class="sub">Résumé rapide de votre compte</p>
    </header>

    <section class="summary-grid">
        <article class="card">
            <h3>Solde</h3>
            <p class="value"><?php echo number_format($userSolde ?? 0,2,',',' ') . ' Ar'; ?></p>
        </article>

        <article class="card">
            <h3>Régime actuel</h3>
            <p class="value"><?php echo !empty($currentRegime) ? htmlspecialchars($currentRegime) : '-'; ?></p>
        </article>

        <article class="card">
            <h3>Prochaine date / Durée restante</h3>
            <p class="value"><?php echo !empty($nextDate) ? htmlspecialchars($nextDate) : 'N/A'; ?>
            <br><small><?php echo isset($remainingDays) ? (int)$remainingDays . ' jours restants' : '-'; ?></small></p>
        </article>
    </section>

    <section class="big-actions">
        <a class="btn" href="/profil">Mon profil</a>
        <a class="btn" href="/regime">Mon régime</a>
        <a class="btn" href="/dashboard">Dashboard</a>
    </section>


    <section class="quick-actions">
        <h4>Actions rapides</h4>
        <div class="actions">
            <a href="/profil/edit" class="qa">Modifier le profil</a>
            <a href="/regime/details" class="qa">Voir les détails du régime</a>
            <a href="/contact" class="qa">Contacter le support</a>
        </div>
    </section>

    <aside class="tip">
        <strong>Astuce :</strong> <?php echo !empty($tip) ? htmlspecialchars($tip) : 'Consultez votre planning pour rester sur la bonne voie.'; ?>
    </aside>

</main>

<?php echo view('partials/Footer'); ?>
</body>
</html>
