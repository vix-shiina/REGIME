<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Modifier code</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <?php $code = $code ?? []; ?>
</head>
<body>
    <?php
        $code = $code ?? [];
        $dateExpirationValue = '';
        if (!empty($code['DateExpiration'])) {
            $dateExpirationValue = date('Y-m-d', strtotime((string) $code['DateExpiration']));
        }
    ?>
    <main class="admin-page">
        <header class="admin-header">
            <h1>Modifier le code #<?= esc((string)($code['Id'] ?? '')) ?></h1>
            <p>Mettre à jour les informations du code.</p>
        </header>

        <section class="admin-panel admin-section">
            <form method="post" action="/admin/codes/update/<?= $code['Id'] ?? '' ?>" class="admin-form">
                <?= csrf_field() ?>
                <div class="field">
                    <label>Code</label>
                    <input type="text" name="Code" value="<?= esc((string)($code['Code'] ?? '')) ?>" required>
                </div>
                <div class="field">
                    <label>Valeur</label>
                    <input type="number" step="0.01" name="Valeur" value="<?= isset($code['Valeur']) ? number_format((float)$code['Valeur'],2) : '' ?>" required>
                </div>
                <div class="field">
                    <label>Date d'expiration</label>
                    <input type="date" name="DateExpiration" value="<?= esc($dateExpirationValue) ?>">
                </div>
                <div class="field">
                    <label><input type="checkbox" name="Actif" value="1" <?= !empty($code['Actif']) ? 'checked' : '' ?>> Actif</label>
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Enregistrer</button>
                    <a class="btn btn-secondary" href="/admin/codes">Annuler</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
