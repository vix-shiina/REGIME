<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Gérer les codes</title>
    <style>label{display:block;margin:8px 0;}input,textarea{width:100%;max-width:480px;padding:6px}</style>
</head>
<body>
    <main>
        <h1>Gérer les codes</h1>

        <section>
            <h2>Créer un code</h2>
            <form method="post" action="/admin/codes/create">
                <?= csrf_field() ?>
                <label>Code
                    <input type="text" name="code" required>
                </label>
                <label>Description
                    <textarea name="description" rows="3"></textarea>
                </label>
                <label>Date d'expiration
                    <input type="date" name="expires_at">
                </label>
                <button type="submit">Créer</button>
            </form>
        </section>
    </main>
</body>
</html>
