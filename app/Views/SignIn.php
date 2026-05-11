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

           <div class="data" style="margin-top:18px;padding:12px 14px;border:1px solid rgba(47,143,81,0.14);border-radius:12px;background:rgba(255,255,255,0.45);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);box-shadow:0 8px 24px rgba(15,23,42,0.06);color:rgba(15,23,42,0.72);font-size:0.92rem;line-height:1.45;">
               <!-- <p style="margin:0 0 6px;opacity:0.82;font-weight:600;">Note de test</p> -->
               <p style="margin:0;opacity:0.72;">admin1@example.com / password1</p>
               <p style="margin:0;opacity:0.72;">miora.rasoanaivo@example.com / password1</p>
                <p style="margin:0;opacity:0.72;">andry.rakoto@example.com / password1</p>
                <p style="margin:0;opacity:0.72;">tahina.razafindrabe@example.com / password1</p>
                <p style="margin:0;opacity:0.72;">feno.randria@example.com / password1</p>    
           </div>
        </form>

        
    </div>
</div>

<?php echo view('partials/Flash'); ?>

</body>
</html>
