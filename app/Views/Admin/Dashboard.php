<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
<main class="admin-page">
    <header class="admin-header">
        <h1>Espace Admin</h1>
        <p>Gestion rapide des principales sections</p>
        <div class="header-actions">
            <a class="btn btn-secondary" href="/SignIn">Retour au login</a>
        </div>
    </header>

    <section class="admin-grid">
        <a class="admin-card" href="/admin/regimes/manage">
            <h2>Gerer les régimes</h2>
            <p>Créer, modifier et supprimer les régimes.</p>
        </a>
        <a class="admin-card" href="/admin/clients/manage">
            <h2>Gerer les Clients</h2>
            <p>Consulter et administrer les comptes utilisateurs.</p>
        </a>
        <a class="admin-card" href="/admin/sports/manage">
            <h2>Gerer les Sports</h2>
            <p>Maintenir la liste des sports et leurs attributs.</p>
        </a>
        <a class="admin-card" href="/admin/codes/manage">
            <h2>Gerer les codes</h2>
            <p>Suivre et mettre à jour les codes disponibles.</p>
        </a>
    </section>
</main>
</body>
</html>
