<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sign In</title>
    <link rel="stylesheet" href="/assets/css/auth.css">
    <script defer src="/assets/js/signin.js"></script>
</head>
<body>
<a class="admin-link" href="#">Acces admin</a>
<div class="auth-page split">
    <div class="left-side">
        <img src="/assets/images/regime.png" alt="regime">
    </div>
    <div class="right-side">
        <form id="signinForm" method="post" action="/SignIn">
            <h2>Connexion</h2>
            <input type="hidden" name="action" value="signin">
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Mot de passe</label>
            <input type="password" name="password" required>
            <button type="submit">Se connecter</button>
            <p class="muted">Pas encore de compte ? <a href="/SignUp">Inscription</a></p>
        </form>
    </div>
</div>

<?php
    $flashSuccess = session()->getFlashdata('flash_success');
    $flashError = session()->getFlashdata('flash_error');
if (!empty($flashSuccess)): ?>
    <div class="toast success"><?php echo htmlspecialchars($flashSuccess); ?></div>
<?php endif; ?>
<?php if (!empty($flashError)): ?>
    <div class="toast error"><?php echo htmlspecialchars($flashError); ?></div>
<?php endif; ?>

</body>
</html>
