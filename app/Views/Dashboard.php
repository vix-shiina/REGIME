<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - regime.com</title>
    <link rel="stylesheet" href="/assets/css/myhome.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-nav { display: flex; flex-wrap: wrap; gap: 10px; margin: 20px 0 30px; border-bottom: 1px solid #e5e7eb; position: sticky; top: 0; background: #fff; z-index: 100; padding-top: 10px; }
        .dashboard-nav a { display: inline-flex; align-items: center; padding: 12px 20px; border-radius: 10px 10px 0 0; background: #f3f5f9; color: #1f2937; font-weight: 600; text-decoration: none; border: 1px solid #e5e7eb; border-bottom: none; transition: all 0.2s; cursor: pointer; }
        .dashboard-nav a:hover { background: #e5e7eb; }
        .dashboard-nav a.is-active { background: #fff; color: #2f8f51; border-top: 3px solid #2f8f51; }
        .dashboard-section { margin-bottom: 60px; padding-top: 30px; }
        .dashboard-section:first-of-type { padding-top: 0; }
    </style>
</head>
<body class="has-promo-header">

<?php echo view('partials/Header'); ?>

<main class="myhome container">
    <header class="welcome">
        <h1>Votre Tableau de Bord</h1>
        <p class="sub">Suivi de votre progression et recommandations</p>
    </header>

    <!-- ===== NAVIGATION ===== -->
    <nav class="dashboard-nav">
        <a href="#graph" class="dashboard-link is-active" data-section="graph">📊 Graphique</a>
        <a href="#regimes" class="dashboard-link" data-section="regimes">🍽️ Régimes</a>
        <a href="#sports" class="dashboard-link" data-section="sports">⚽ Sports</a>
    </nav>

    <!-- ===== SECTION 1: GRAPH ===== -->
    <section id="graph" class="dashboard-section">
        <div style="background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 8px 24px rgba(14,20,30,0.06); border: 1px solid rgba(15,23,42,0.10);">
            <h3 style="margin: 0 0 16px; color: #2f8f51; font-size: 1.1rem;">Évolution de votre poids</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div style="background: linear-gradient(90deg, rgba(47,143,81,0.06), #fff); padding: 16px; border-radius: 10px; border: 1px solid rgba(47,143,81,0.1);">
                    <div style="font-size: 0.9rem; color: #666; margin-bottom: 8px;">Poids Actuel</div>
                    <div style="font-size: 2rem; font-weight: 800; color: #0b1720;">
                        <?php echo !empty($historique) ? end($historique)['Poids'] : '0'; ?> <span style="font-size: 1.2rem;">kg</span>
                    </div>
                </div>
                
                <div style="background: linear-gradient(90deg, rgba(47,143,81,0.06), #fff); padding: 16px; border-radius: 10px; border: 1px solid rgba(47,143,81,0.1);">
                    <div style="font-size: 0.9rem; color: #666; margin-bottom: 8px;">Mettre à jour</div>
                    <form action="<?= base_url('dashboard/ajouterPoids') ?>" method="post" style="display: flex; gap: 8px;">
                        <input type="hidden" name="userId" value="<?= $userId ?? '' ?>">
                        <input type="number" step="0.1" name="poids" placeholder="Ex: 75.5" 
                               style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.95rem;">
                        <button type="submit" style="padding: 10px 16px; background: linear-gradient(90deg, #2f8f51, #4fbf7a); color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">OK</button>
                    </form>
                </div>
            </div>
            
            <div style="height: 300px; position: relative;">
                <canvas id="evolutionChart" style="width: 100%;"></canvas>
            </div>

            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <button class="dashboard-link" data-section="regimes" style="flex: 1; padding: 12px; background: linear-gradient(90deg, #2f8f51, #4fbf7a); color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Voir les régimes →</button>
            </div>
        </div>
    </section>

    <!-- ===== SECTION 2: REGIMES ===== -->
    <section id="regimes" class="dashboard-section">
        <div>
            <h2 style="margin: 0 0 20px; color: #2f8f51; font-size: 1.4rem;">Régimes suggérés pour vous</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                <?php if (!empty($regimes)): ?>
                    <?php foreach ($regimes as $r): ?>
                    <div style="background: linear-gradient(180deg, #fff, #fbfdff); border-radius: 12px; padding: 20px; box-shadow: 0 8px 24px rgba(14,20,30,0.06); border: 1px solid rgba(15,23,42,0.10); border-top: 4px solid #2f8f51;">
                        <h4 style="margin: 0 0 12px; color: #0b1720; font-size: 1.1rem;"><?= htmlspecialchars($r['RegimeNom'] ?? '') ?></h4>
                        <div style="margin-bottom: 16px;">
                            <div style="font-size: 2rem; font-weight: 800; color: #2f8f51;">
                                <?= number_format($r['PrixJournaliere'], 0, '.', ' ') ?> <span style="font-size: 0.8rem; color: #666;">Ar/semaine</span>
                            </div>
                        </div>
                        <ul style="margin: 16px 0; padding-left: 20px; color: #333; font-size: 0.95rem;">
                            <li><strong>Viande :</strong> <?= (float)$r['Viande'] ?>%</li>
                            <li><strong>Poisson :</strong> <?= (float)$r['Poisson'] ?>%</li>
                            <li><strong>Volaille :</strong> <?= (float)$r['Volailles'] ?>%</li>
                        </ul>
                        <button style="width: 100%; padding: 12px; background: linear-gradient(90deg, #2f8f51, #4fbf7a); color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Choisir ce régime</button>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: #666;">Aucun régime suggéré pour le moment.</p>
                <?php endif; ?>
            </div>

            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <button class="dashboard-link" data-section="graph" style="flex: 1; padding: 12px; background: #e5e7eb; color: #1f2937; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">← Retour au graphique</button>
                <button class="dashboard-link" data-section="sports" style="flex: 1; padding: 12px; background: linear-gradient(90deg, #2f8f51, #4fbf7a); color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Voir les sports →</button>
            </div>
        </div>
    </section>

    <!-- ===== SECTION 3: SPORTS ===== -->
    <section id="sports" class="dashboard-section">
        <div>
            <h2 style="margin: 0 0 20px; color: #2f8f51; font-size: 1.4rem;">Sports recommandés</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                <?php if (!empty($sports)): ?>
                    <?php foreach ($sports as $s): ?>
                    <div style="background: linear-gradient(180deg, #fff, #fbfdff); border-radius: 12px; padding: 20px; box-shadow: 0 8px 24px rgba(14,20,30,0.06); border: 1px solid rgba(15,23,42,0.10); border-top: 4px solid #4fbf7a;">
                        <h4 style="margin: 0 0 12px; color: #0b1720; font-size: 1.1rem;"><?= htmlspecialchars($s['SportNom'] ?? '') ?></h4>
                        <div style="margin-bottom: 16px;">
                            <div style="font-size: 2rem; font-weight: 800; color: #2f8f51;">
                                <?= (float)$s['EfficacitePoids'] ?> <span style="font-size: 0.8rem; color: #666;">kg/semaine</span>
                            </div>
                        </div>
                        <ul style="margin: 16px 0; padding-left: 20px; color: #333; font-size: 0.95rem;">
                            <li><strong>Catégorie :</strong> <?= htmlspecialchars($s['Categorie'] ?? 'N/A') ?></li>
                        </ul>
                        <button style="width: 100%; padding: 12px; background: linear-gradient(90deg, #4fbf7a, #6bd188); color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Sélectionner</button>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: #666;">Aucun sport suggéré pour le moment.</p>
                <?php endif; ?>
            </div>

            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <button class="dashboard-link" data-section="regimes" style="flex: 1; padding: 12px; background: #e5e7eb; color: #1f2937; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">← Retour aux régimes</button>
                <a href="/myhome" style="flex: 1; padding: 12px; background: linear-gradient(90deg, #2f8f51, #4fbf7a); color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; text-align: center; text-decoration: none;">Retour à l'accueil</a>
            </div>
        </div>
    </section>

</main>

<?php echo view('partials/Footer'); ?>

<script>
    // Navigation vers sections avec scroll fluide
    document.querySelectorAll('.dashboard-link').forEach(link => {
        link.addEventListener('click', function(e) {
            const section = this.getAttribute('data-section');
            if (section) {
                e.preventDefault();
                
                const targetElement = document.getElementById(section);
                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    
                    // Mettre à jour la navigation active
                    document.querySelectorAll('.dashboard-nav a').forEach(a => a.classList.remove('is-active'));
                    document.querySelector(`.dashboard-nav a[data-section="${section}"]`)?.classList.add('is-active');
                }
            }
        });
    });

    // Graphique
    document.addEventListener("DOMContentLoaded", function() {
        const canvasElement = document.getElementById('evolutionChart');
        
        if (canvasElement) {
            const ctx = canvasElement.getContext('2d');
            
            const labels = <?= json_encode(array_column($historique ?? [], 'DateEvolution')) ?>;
            const weights = <?= json_encode(array_column($historique ?? [], 'Poids')) ?>;

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Poids (kg)',
                        data: weights,
                        borderColor: '#2f8f51',
                        backgroundColor: 'rgba(47, 143, 81, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 6,
                        pointBackgroundColor: '#2f8f51',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true, position: 'top' }
                    },
                    scales: {
                        y: { 
                            beginAtZero: false,
                            grid: { color: 'rgba(47, 143, 81, 0.05)' }
                        },
                        x: { 
                            grid: { display: false }
                        }
                    }
                }
            });
        }
        
        // Marquer la première section comme active au chargement
        document.querySelector('.dashboard-nav a:first-child')?.classList.add('is-active');
    });
</script>

</body>
</html>