<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les régimes</title>
    <style>label{display:block;margin:8px 0;}input[type=text],input[type=number],textarea{width:100%;max-width:400px;padding:6px}</style>
</head>
<body>
    <main>
        <h1>Gérer les régimes</h1>

        <section>
            <h2>Créer un régime</h2>
            <form method="post" action="/admin/regimes/create">
                <?= csrf_field() ?>
                <label>Nom du régime
                    <input type="text" name="RegimeNom" required>
                </label>
                <label>Type de régime (ID)
                    <input type="number" name="TypeDeRegimeId">
                </label>
                <label>Prix journalière
                    <input type="number" step="0.01" name="PrixJournaliere">
                </label>
                <label>Efficacité (poids)
                    <input type="number" step="0.01" name="EfficacitePoids">
                </label>
                <label>Viande (%)
                    <input type="number" name="Viande" step="0.01" min="0" max="100" placeholder="0.00">
                </label>
                <label>Poisson (%)
                    <input type="number" name="Poisson" step="0.01" min="0" max="100" placeholder="0.00">
                </label>
                <label>Volailles (%)
                    <input type="number" name="Volailles" step="0.01" min="0" max="100" placeholder="0.00">
                </label>
                <button type="submit">Créer</button>
            </form>
        </section>

        <section style="margin-top:24px">
            <h2>Supprimer un régime (par ID)</h2>
            <form method="post" action="/admin/regimes/delete/">
                <?= csrf_field() ?>
                <label>ID du régime
                    <input type="number" name="id" required>
                </label>
                <p>Remarque : utilisez l'URL `/admin/regimes/delete/{id}` pour supprimer directement.</p>
            </form>
        </section>
    </main>
</body>
</html>