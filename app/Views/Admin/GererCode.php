<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Gérer les codes</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
    <?php
        $codes = $codes ?? [];
        $formatDate = static function ($value): string {
            if (empty($value)) {
                return '';
            }
            return date('Y-m-d', strtotime((string) $value));
        };
    ?>
    <main class="admin-page">
        <header class="admin-header">
            <h1>Gérer les codes</h1>
            <p>Choisissez l’action à effectuer sur les codes.</p>
        </header>

        <nav class="admin-toolbar">
            <a href="#create" class="is-active">Créer</a>
            <a href="#list">Liste</a>
            <a href="/admin-dashboard">Retour dashboard</a>
        </nav>

        <section id="create" class="admin-panel admin-section">
            <h2>Créer un code</h2>
            <form method="post" action="/admin/codes/create" class="admin-form">
                <?= csrf_field() ?>
                <div class="field">
                    <label>Code</label>
                    <input type="text" name="Code" required>
                </div>
                <div class="field">
                    <label>Valeur</label>
                    <input type="number" step="0.01" name="Valeur" required>
                </div>
                <div class="field">
                    <label>Date d'expiration</label>
                    <input type="date" name="DateExpiration">
                </div>
                <div class="field">
                    <label><input type="checkbox" name="Actif" value="1" checked> Actif</label>
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Créer</button>
                </div>
            </form>
        </section>

        <section id="list" class="admin-panel admin-section">
            <h2>Liste des codes</h2>
            <?php if (empty($codes)): ?>
                <p class="admin-note">Aucun code trouvé.</p>
            <?php else: ?>
                <table style="width:100%;border-collapse:collapse">
                    <thead>
                        <tr style="text-align:left;border-bottom:1px solid #e5e7eb">
                            <th>ID</th>
                            <th>Code</th>
                            <th>Valeur</th>
                            <th>Expiration</th>
                            <th>Actif</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($codes as $c): ?>
                            <tr style="border-bottom:1px solid #f3f4f6">
                                <td style="padding:10px 8px"><?= esc((string)($c['Id'] ?? '')) ?></td>
                                <td style="padding:10px 8px"><?= esc((string)($c['Code'] ?? '')) ?></td>
                                <td style="padding:10px 8px"><?= isset($c['Valeur']) ? number_format((float)$c['Valeur'],2) : '' ?></td>
                                <td style="padding:10px 8px"><?= esc($formatDate($c['DateExpiration'] ?? null)) ?></td>
                                <td style="padding:10px 8px"><?= !empty($c['Actif']) ? 'Oui' : 'Non' ?></td>
                                <td style="padding:10px 8px">
                                    <a class="btn btn-secondary" href="/admin/codes/edit/<?= $c['Id'] ?>">Modifier</a>
                                    <form method="post" action="/admin/codes/delete/<?= $c['Id'] ?>" style="display:inline-block;margin-left:8px" onsubmit="return confirm('Confirmer la suppression du code ID <?= $c['Id'] ?> ?')">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-danger" type="submit">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
