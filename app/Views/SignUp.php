<?php
$genres = [];
try {
    $dsn = 'mysql:host=127.0.0.1;dbname=REGIME;charset=utf8mb4';
    $pdo = new PDO($dsn, 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $stmt = $pdo->query('SELECT Id, Genre FROM Genre');
    $genres = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
     catch (Exception $e) {
    }
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sign Up</title>
    <link rel="stylesheet" href="/assets/css/auth.css">
    <script defer src="/assets/js/signup.js"></script>
</head>
<body>
<a class="admin-link" href="/admin">Acces admin</a>
<div class="auth-page split">
    <div class="left-side">
        <img src="/assets/images/regime.png" alt="regime">
    </div>
    <div class="right-side">
        <form id="signupForm" method="post" action="/SignUp">
            <h2>Inscription</h2>
            <input type="hidden" name="action" value="signup">
            <label>Nom</label>
            <input type="text" name="nom" required>
            <label>Prénom</label>
            <input type="text" name="prenom" required>
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Mot de passe</label>
            <input type="password" name="password" required>
            <label>Genre</label>
            <select name="genre_id" required>
                <option value="">-- Choisir --</option>
                <?php foreach ($genres as $g): ?>
                    <option value="<?php echo htmlspecialchars($g['Id']); ?>"><?php echo htmlspecialchars($g['Genre']); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">S'inscrire</button>
            <p class="muted">Déjà inscrit ? <a href="/SignIn">Se connecter</a></p>
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
