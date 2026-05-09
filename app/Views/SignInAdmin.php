<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Sign In</title>
    <link rel="stylesheet" href="/assets/css/auth.css">
    <script defer src="/assets/js/signin.js"></script>
</head>
<body>
<div class="auth-page split">
    <div class="left-side">
        <img src="/assets/images/regime.png" alt="regime">
    </div>
    <div class="right-side">
        <form id="signinForm" method="post" action="/admin">
            <h2>Connexion Admin</h2>
            <input type="hidden" name="action" value="admin">
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Mot de passe</label>
            <input type="password" name="password" required>
            <button type="submit">Se connecter</button>
            <p class="muted"><a href="/index.php/SignIn">Retour</a></p>
        </form>
    </div>
</div>

<?php echo view('partials/Flash'); ?>

</body>
</html>
