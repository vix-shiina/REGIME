<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Gérer les sports</title>
    <style>label{display:block;margin:8px 0;}input[type=text],input[type=number]{width:100%;max-width:400px;padding:6px}</style>
</head>
<body>
    <main>
        <h1>Gérer les sports</h1>

        <section>
            <h2>Créer un sport</h2>
            <form method="post" action="/admin/sports/create">
                <?= csrf_field() ?>
                <label>Nom du sport
                    <input type="text" name="SportNom" required>
                </label>
                <label>Type de sport (ID)
                    <input type="number" name="TypeDeSportId">
                </label>
                <label>Efficacité (poids)
                    <input type="number" step="0.01" name="EfficacitePoids">
                </label>
                <button type="submit">Créer</button>
            </form>
        </section>

        <section style="margin-top:24px">
            <h2>Supprimer un sport (par ID)</h2>
            <form method="post" action="/admin/sports/delete/">
                <?= csrf_field() ?>
                <label>ID du sport
                    <input type="number" name="id" required>
                </label>
            </form>
        </section>
    </main>
</body>
</html>
