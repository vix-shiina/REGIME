<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Dashboard</title>
    <meta name="theme-color" content="#2f8f51">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <link rel="stylesheet" href="/assets/css/admin-dashboard.css">
</head>
<body class="admin-dashboard-body">
<main class="admin-dashboard shell">
    <section class="admin-hero">
        <div class="admin-hero-copy">
            <p class="eyebrow">Espace Admin</p>
            <h1>Tableau de bord de gestion</h1>
            <p class="lead">Une vue claire pour piloter les régimes, les sports, les clients et les codes avec le même esprit visuel que le dashboard utilisateur.</p>
        </div>

        <div class="admin-hero-actions">
            <a class="admin-pill admin-pill-secondary" href="/myhome">Voir le dashboard client</a>
            <a class="admin-pill admin-pill-ghost" href="/SignIn">Retour au login</a>
        </div>
    </section>

    <section class="admin-stats" aria-label="Raccourcis de gestion">
        <article class="admin-stat-card">
            <span class="admin-stat-label">Accès rapide</span>
            <strong>Régimes</strong>
            <p>Créer, modifier et supprimer les régimes disponibles.</p>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-label">Accès rapide</span>
            <strong>Sports</strong>
            <p>Maintenir la liste des sports et leurs attributs.</p>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-label">Accès rapide</span>
            <strong>Clients</strong>
            <p>Consulter et administrer les comptes utilisateurs.</p>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-label">Accès rapide</span>
            <strong>Codes</strong>
            <p>Suivre et mettre à jour les codes disponibles.</p>
        </article>
    </section>

    <section class="admin-grid" aria-label="Sections de gestion">
        <a class="admin-card" href="/admin/regimes/manage">
            <div class="admin-card-icon">01</div>
            <h2>Gérer les régimes</h2>
            <p>Créer, modifier et supprimer les régimes.</p>
            <span class="admin-card-link">Ouvrir la gestion</span>
        </a>
        <a class="admin-card" href="/admin/clients/manage">
            <div class="admin-card-icon">02</div>
            <h2>Gérer les clients</h2>
            <p>Consulter et administrer les comptes utilisateurs.</p>
            <span class="admin-card-link">Ouvrir la gestion</span>
        </a>
        <a class="admin-card" href="/admin/sports/manage">
            <div class="admin-card-icon">03</div>
            <h2>Gérer les sports</h2>
            <p>Maintenir la liste des sports et leurs attributs.</p>
            <span class="admin-card-link">Ouvrir la gestion</span>
        </a>
        <a class="admin-card" href="/admin/codes/manage">
            <div class="admin-card-icon">04</div>
            <h2>Gérer les codes</h2>
            <p>Suivre et mettre à jour les codes disponibles.</p>
            <span class="admin-card-link">Ouvrir la gestion</span>
        </a>
    </section>
</main>

<script src="/assets/js/admin-dashboard.js" defer></script>
</body>
</html>
