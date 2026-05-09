<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Myhome</title>
    <link rel="stylesheet" href="/assets/css/profile.css">
</head>
<body>

<?php echo view('partials/Header'); ?>
<main class="profile-page" style="min-height:100vh;display:grid;place-items:center;">
    <section class="card" style="padding:40px 48px;text-align:center;">
        <h1 style="margin:0;font-size:clamp(2rem,5vw,4rem);letter-spacing:-0.05em;">
            Bienvenu <?php echo htmlspecialchars($userName ?? 'Utilisateur'); ?>
        </h1>
    </section>
</main>
<?php echo view('partials/Footer'); ?>
</body>
</html>
