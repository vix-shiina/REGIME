<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les régimes</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <style>
    /* Page-specific layout: two-column form, percentages aligned right */
    .admin-form .form-grid{display:grid;grid-template-columns:1fr 220px;gap:16px;align-items:start}
    .admin-form .form-grid .col .field{margin-bottom:12px}
    .admin-form .form-grid .percentages .field{display:flex;flex-direction:column;align-items:flex-end}
    .admin-form .form-grid .percentages input{text-align:right;width:80%}
    @media (max-width:720px){.admin-form .form-grid{grid-template-columns:1fr}.admin-form .form-grid .percentages input{width:100%}}
    </style>
</head>
<body>
    <?php $typeDeRegimeOptions = $typeDeRegimeOptions ?? []; ?>
    <main class="admin-page">
        <header class="admin-header">
            <h1>Gérer les régimes</h1>
            <p>Choisissez une action et remplissez le formulaire correspondant.</p>
        </header>

        <nav class="admin-toolbar">
            <a href="#create" class="is-active">Créer</a>
            <a href="#delete">Supprimer</a>
            <a href="/admin-dashboard">Retour dashboard</a>
        </nav>

        <section id="create" class="admin-panel admin-section">
            <h2>Créer un régime</h2>
            <form method="post" action="/admin/regimes/create" class="admin-form">
                <?= csrf_field() ?>
                <div class="form-grid">
                    <div class="col">
                        <div class="field">
                            <label>Nom du régime</label>
                            <input type="text" name="RegimeNom" required>
                        </div>
                        <div class="field">
                            <label>Type de régime</label>
                            <select name="TypeDeRegimeId" required>
                                <option value="">Sélectionnez un type</option>
                                <?php foreach ($typeDeRegimeOptions as $option): ?>
                                    <option value="<?= $option['Id'] ?>"><?= $option['TypeDeRegime'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="field">
                            <label>Prix journalière</label>
                            <input type="number" step="0.01" name="PrixJournaliere">
                        </div>
                        <div class="field">
                            <label>Efficacité (poids)</label>
                            <input type="number" step="0.01" name="EfficacitePoidsParSemaine">
                        </div>
                    </div>
                    <div class="col percentages">
                        <div class="field">
                            <label>Viande (%)</label>
                            <input type="number" name="Viande" step="0.01" min="0" max="100" placeholder="0.00">
                        </div>
                        <div class="field">
                            <label>Poisson (%)</label>
                            <input type="number" name="Poisson" step="0.01" min="0" max="100" placeholder="0.00">
                        </div>
                        <div class="field">
                            <label>Volailles (%)</label>
                            <input type="number" name="Volailles" step="0.01" min="0" max="100" placeholder="0.00">
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Créer</button>
                </div>
            </form>
        </section>

        <section id="list" class="admin-panel admin-section">
            <h2>Liste des régimes</h2>
            <?php $regimes = $regimes ?? []; ?>
            <?php if (empty($regimes)): ?>
                <p class="admin-note">Aucun régime trouvé.</p>
            <?php else: ?>
                <table style="width:100%;border-collapse:collapse">
                    <thead>
                        <tr style="text-align:left;border-bottom:1px solid #e5e7eb">
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Type</th>
                            <th>Prix</th>
                            <th>Efficacité</th>
                            <th>Viande</th>
                            <th>Poisson</th>
                            <th>Volailles</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($regimes as $r): ?>
                            <tr style="border-bottom:1px solid #f3f4f6">
                                <td style="padding:10px 8px"><?= $r['Id'] ?></td>
                                <td style="padding:10px 8px"><?= esc((string)($r['RegimeNom'] ?? '')) ?></td>
                                <td style="padding:10px 8px"><?= esc((string)($r['TypeNom'] ?? $r['TypeDeRegime'] ?? '')) ?></td>
                                <td style="padding:10px 8px"><?= isset($r['PrixJournaliere']) ? number_format((float)$r['PrixJournaliere'],2) : '' ?></td>
                                <td style="padding:10px 8px"><?= isset($r['EfficacitePoidsParSemaine']) ? number_format((float)$r['EfficacitePoidsParSemaine'],2) : '' ?></td>
                                <td style="padding:10px 8px"><?= isset($r['Viande']) ? number_format((float)$r['Viande'],2) . '%' : '' ?></td>
                                <td style="padding:10px 8px"><?= isset($r['Poisson']) ? number_format((float)$r['Poisson'],2) . '%' : '' ?></td>
                                <td style="padding:10px 8px"><?= isset($r['Volailles']) ? number_format((float)$r['Volailles'],2) . '%' : '' ?></td>
                                <td style="padding:10px 8px">
                                    <a class="btn btn-secondary" href="/admin/regimes/edit/<?= $r['Id'] ?>">Modifier</a>
                                    <form method="post" action="/admin/regimes/delete/<?= $r['Id'] ?>" style="display:inline-block;margin-left:8px" onsubmit="return confirm('Confirmer la suppression du régime ID <?= $r['Id'] ?> ?')">
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