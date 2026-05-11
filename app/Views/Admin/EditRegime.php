<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Modifier régime</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <?php $regime = $regime ?? []; $typeDeRegimeOptions = $typeDeRegimeOptions ?? []; ?>
</head>
<body>
    <main class="admin-page">
        <header class="admin-header">
            <h1>Modifier le régime #<?= esc((string)($regime['Id'] ?? '')) ?></h1>
            <p>Mettre à jour les informations du régime.</p>
        </header>

        <section class="admin-panel admin-section">
            <form method="post" action="/admin/regimes/update/<?= $regime['Id'] ?? '' ?>" class="admin-form">
                <?= csrf_field() ?>
                <div class="field">
                    <label>Nom du régime</label>
                    <input type="text" name="RegimeNom" value="<?= esc((string)($regime['RegimeNom'] ?? '')) ?>" required>
                </div>
                <div class="field">
                    <label>Type de régime</label>
                    <select name="TypeDeRegimeId" required>
                        <option value="">Sélectionnez un type</option>
                        <?php foreach ($typeDeRegimeOptions as $option): ?>
                            <option value="<?= $option['Id'] ?>" <?= isset($regime['TypeDeRegimeId']) && $regime['TypeDeRegimeId']==$option['Id'] ? 'selected' : '' ?>><?= esc((string)($option['TypeDeRegime'] ?? ($option['TypeNom'] ?? ''))) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label>Prix journalière</label>
                    <input type="number" step="0.01" name="PrixJournaliere" value="<?= isset($regime['PrixJournaliere']) ? number_format((float)$regime['PrixJournaliere'],2) : '' ?>">
                </div>
                <div class="field">
                    <label>Efficacité (poids)</label>
                    <input type="number" step="0.01" name="EfficacitePoidsParSemaine" value="<?= isset($regime['EfficacitePoidsParSemaine']) ? number_format((float)$regime['EfficacitePoidsParSemaine'],2) : '' ?>">
                </div>
                <div class="field">
                    <label>Viande (%)</label>
                    <input type="number" name="Viande" step="0.01" min="0" max="100" value="<?= isset($regime['Viande']) ? number_format((float)$regime['Viande'],2) : '' ?>">
                </div>
                <div class="field">
                    <label>Poisson (%)</label>
                    <input type="number" name="Poisson" step="0.01" min="0" max="100" value="<?= isset($regime['Poisson']) ? number_format((float)$regime['Poisson'],2) : '' ?>">
                </div>
                <div class="field">
                    <label>Volailles (%)</label>
                    <input type="number" name="Volailles" step="0.01" min="0" max="100" value="<?= isset($regime['Volailles']) ? number_format((float)$regime['Volailles'],2) : '' ?>">
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Enregistrer</button>
                    <a class="btn btn-secondary" href="/admin/regimes">Annuler</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
