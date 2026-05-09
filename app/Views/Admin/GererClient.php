<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Gérer les clients</title>
    <style>label{display:block;margin:8px 0;}input[type=text],input[type=email],input[type=password]{width:100%;max-width:400px;padding:6px}</style>
</head>
<body>
    <main>
        <h1>Gérer les clients</h1>

        <section>
            <h2>Créer un client</h2>
            <form method="post" action="/admin/clients/create">
                <?= csrf_field() ?>
                <label>Nom complet
                    <input type="text" name="name" required>
                </label>
                <label>Email
                    <input type="email" name="email" required>
                </label>
                <label>Mot de passe
                    <input type="password" name="password">
                </label>
                <label>Rôle
                    <input type="text" name="role" placeholder="admin/user">
                </label>
                <button type="submit">Créer</button>
            </form>
        </section>

        <section style="margin-top:24px">
            <h2>Recherche client</h2>
            <form method="get" action="/admin/clients">
                <label>Recherche par email ou nom
                    <input type="text" name="q">
                </label>
                <button type="submit">Rechercher</button>
            </form>
        </section>
    </main>
</body>
</html>
