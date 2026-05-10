<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Modifier sport</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <?php $sport = $sport ?? []; $typeOptions = $typeOptions ?? []; ?>
</head>
<body>
    <main class="admin-page">
        <header class="admin-header">
            <h1>Modifier le sport #<?= esc((string)($sport['Id'] ?? '')) ?></h1>
            <p>Mettre à jour les informations du sport.</p>
        </header>

        <section class="admin-panel admin-section">
            <form method="post" action="/admin/sports/update/<?= $sport['Id'] ?? '' ?>" class="admin-form">
                <?= csrf_field() ?>
                <div class="field">
                    <label>Nom du sport</label>
                    <input type="text" name="SportNom" value="<?= esc((string)($sport['SportNom'] ?? '')) ?>" required>
                </div>
                <div class="field">
                    <label>Type de sport</label>
                    <select name="TypeDeSportId">
                        <option value="">Sélectionnez une catégorie</option>
                        <?php foreach ($typeOptions as $opt): ?>
                            <option value="<?= $opt['Id'] ?>" <?= isset($sport['TypeDeSportId']) && $sport['TypeDeSportId']==$opt['Id'] ? 'selected' : '' ?>><?= esc((string)($opt['TypeNom'] ?? $opt['TypeDeSport'] ?? '')) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label>Efficacité (poids)</label>
                    <input type="number" step="0.01" name="EfficacitePoids" value="<?= isset($sport['EfficacitePoids']) ? number_format((float)$sport['EfficacitePoids'],2) : '' ?>">
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Enregistrer</button>
                    <a class="btn btn-secondary" href="/admin/sports">Annuler</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
