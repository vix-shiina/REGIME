<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Gérer les sports</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
    <main class="admin-page">
        <header class="admin-header">
            <h1>Gérer les sports</h1>
            <p>Choisissez une action et utilisez le formulaire adapté.</p>
        </header>

        <nav class="admin-toolbar">
            <a href="#create" class="is-active">Créer</a>
            <a href="#delete">Supprimer</a>
            <a href="/admin-dashboard">Retour dashboard</a>
        </nav>

        <section id="create" class="admin-panel admin-section">
            <h2>Créer un sport</h2>
            <form method="post" action="/admin/sports/create" class="admin-form">
                <?= csrf_field() ?>
                <div class="field">
                    <label>Nom du sport</label>
                    <input type="text" name="SportNom" required>
                </div>
                <div class="field">
                    <label>Type de sport</label>
                    <select name="TypeDeSportId">
                        <option value="">Sélectionnez une catégorie</option>
                        <?php $typeOptions = $typeOptions ?? []; ?>
                        <?php foreach ($typeOptions as $opt): ?>
                            <option value="<?= $opt['Id'] ?>"><?= esc((string)($opt['TypeNom'] ?? $opt['TypeDeSport'] ?? '')) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label>Efficacité (poids)</label>
                    <input type="number" step="0.01" name="EfficacitePoids">
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Créer</button>
                </div>
            </form>
        </section>

        <section id="list" class="admin-panel admin-section">
            <h2>Liste des sports</h2>
            <?php $sports = $sports ?? []; ?>
            <?php if (empty($sports)): ?>
                <p class="admin-note">Aucun sport trouvé.</p>
            <?php else: ?>
                <table style="width:100%;border-collapse:collapse">
                    <thead>
                        <tr style="text-align:left;border-bottom:1px solid #e5e7eb">
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Catégorie</th>
                            <th>Efficacité</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sports as $s): ?>
                            <tr style="border-bottom:1px solid #f3f4f6">
                                <td style="padding:10px 8px"><?= esc((string)($s['Id'] ?? '')) ?></td>
                                <td style="padding:10px 8px"><?= esc((string)($s['SportNom'] ?? '')) ?></td>
                                <td style="padding:10px 8px"><?= esc((string)($s['Categorie'] ?? '')) ?></td>
                                <td style="padding:10px 8px"><?= isset($s['EfficacitePoids']) ? number_format((float)$s['EfficacitePoids'],2) : '' ?></td>
                                <td style="padding:10px 8px">
                                    <a class="btn btn-secondary" href="/admin/sports/edit/<?= $s['Id'] ?>">Modifier</a>
                                    <form method="post" action="/admin/sports/delete/<?= $s['Id'] ?>" style="display:inline-block;margin-left:8px" onsubmit="return confirm('Confirmer la suppression du sport ID <?= $s['Id'] ?> ?')">
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
