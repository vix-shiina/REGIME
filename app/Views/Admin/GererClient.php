<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Gérer les clients</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
    <?php
        $clients = $clients ?? [];
        $search = $search ?? '';
        $page = (int) ($page ?? 1);
        $totalPages = (int) ($totalPages ?? 1);
        $perPage = (int) ($perPage ?? 12);
    ?>
    <main class="admin-page">
        <header class="admin-header">
            <h1>Gérer les clients</h1>
            <p>Recherche, affichage du régime actuel et pagination pour garder la liste lisible.</p>
        </header>

        <nav class="admin-toolbar">
            <a href="#create" class="is-active">Créer</a>
            <a href="#search">Rechercher</a>
            <a href="#list">Liste</a>
            <a href="/admin-dashboard">Retour dashboard</a>
        </nav>

        <section id="create" class="admin-panel admin-section">
            <h2>Créer un client</h2>
            <form method="post" action="/admin/clients/create" class="admin-form">
                <?= csrf_field() ?>
                <div class="field">
                    <label>Nom complet</label>
                    <input type="text" name="name" required>
                </div>
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="field">
                    <label>Mot de passe</label>
                    <input type="password" name="password">
                </div>
                <div class="field">
                    <label>Rôle</label>
                    <input type="text" name="role" placeholder="admin/user">
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Créer</button>
                </div>
            </form>
        </section>

        <section id="search" class="admin-panel admin-section">
            <h2>Recherche client</h2>
            <form method="get" action="/admin/clients" class="admin-form">
                <div class="field">
                    <label>Recherche par email ou nom</label>
                    <input type="text" name="q" value="<?= esc($search) ?>" placeholder="Nom, prénom ou email">
                </div>
                <div class="form-actions">
                    <button class="btn btn-secondary" type="submit">Rechercher</button>
                    <?php if ($search !== ''): ?>
                        <a class="btn btn-ghost" href="/admin/clients">Réinitialiser</a>
                    <?php endif; ?>
                </div>
            </form>
        </section>

        <section id="list" class="admin-panel admin-section">
            <h2>Liste des clients</h2>
            <p class="admin-note"><?= esc((string) count($clients)) ?> client(s) affiché(s) sur <?= esc((string) $totalPages) ?> page(s).</p>

            <?php if (empty($clients)): ?>
                <p class="admin-note">Aucun client trouvé.</p>
            <?php else: ?>
                <div style="overflow-x:auto">
                    <table style="width:100%;border-collapse:collapse;min-width:900px">
                        <thead>
                            <tr style="text-align:left;border-bottom:1px solid #e5e7eb">
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Genre</th>
                                <th>Âge</th>
                                <th>Taille</th>
                                <th>Poids</th>
                                <th>Régime actuel</th>
                                <th>Date début</th>
                                <th>Durée</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clients as $client): ?>
                                <tr style="border-bottom:1px solid #f3f4f6">
                                    <td style="padding:10px 8px"><?= esc((string) ($client['Id'] ?? '')) ?></td>
                                    <td style="padding:10px 8px"><?= esc((string) ($client['Nom'] ?? '')) ?></td>
                                    <td style="padding:10px 8px"><?= esc((string) ($client['Prenom'] ?? '')) ?></td>
                                    <td style="padding:10px 8px"><?= esc((string) ($client['Email'] ?? '')) ?></td>
                                    <td style="padding:10px 8px"><?= esc((string) ($client['UserType'] ?? '-')) ?></td>
                                    <td style="padding:10px 8px"><?= esc((string) ($client['Genre'] ?? '-')) ?></td>
                                    <td style="padding:10px 8px"><?= esc((string) ($client['Age'] ?? '-')) ?></td>
                                    <td style="padding:10px 8px"><?= isset($client['Taille']) ? esc((string) $client['Taille']) : '-' ?></td>
                                    <td style="padding:10px 8px"><?= isset($client['Poids']) ? esc((string) $client['Poids']) : '-' ?></td>
                                    <td style="padding:10px 8px"><?= esc((string) ($client['RegimeNom'] ?? '-')) ?></td>
                                    <td style="padding:10px 8px"><?= esc((string) ($client['DateDebut'] ?? '-')) ?></td>
                                    <td style="padding:10px 8px"><?= esc((string) ($client['DureeEnJours'] ?? '-')) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="form-actions" style="margin-top:16px;justify-content:space-between">
                    <div>
                        <span class="admin-note">Page <?= esc((string) $page) ?> / <?= esc((string) $totalPages) ?></span>
                    </div>
                    <div style="display:flex;gap:10px;flex-wrap:wrap">
                        <?php $baseQuery = $search !== '' ? '&q=' . urlencode($search) : ''; ?>
                        <?php if ($page > 1): ?>
                            <a class="btn btn-secondary" href="/admin/clients?page=<?= $page - 1 ?><?= $baseQuery ?>">Précédent</a>
                        <?php endif; ?>
                        <?php if ($page < $totalPages): ?>
                            <a class="btn btn-secondary" href="/admin/clients?page=<?= $page + 1 ?><?= $baseQuery ?>">Suivant</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
