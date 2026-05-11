<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - regime.com</title>
    <link rel="stylesheet" href="/assets/css/myhome.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="has-promo-header">

<?php echo view('partials/Header'); ?>

<main class="myhome container">
    <header class="welcome">
        <h1>Votre Tableau de Bord</h1>
        <p class="sub">Suivi de votre progression</p>
    </header>

    <section id="graph" class="dashboard-section">
        <div style="background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 8px 24px rgba(14,20,30,0.06); border: 1px solid rgba(15,23,42,0.10);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; color: #2f8f51; font-size: 1.1rem;">Évolution de votre poids</h3>
                <div style="font-size: 0.9rem; color: #666;">
                    Date d'aujourd'hui : <strong><?= date('d/m/Y') ?></strong>
                </div>
            </div>
            
            <div style="height: 300px; position: relative; margin-bottom: 20px;">
                <canvas id="evolutionChart" style="width: 100%;"></canvas>
            </div>

            <div style="display: flex; gap: 10px;">
                <form id="evolutionForm" action="<?= base_url('dashboard/ajouterPoids') ?>" method="post" style="display: flex; gap: 8px; flex: 1;">
                    <input type="hidden" name="userId" value="<?= $userId ?? '' ?>">
                    <input type="date" name="dateEvolution" value="<?= date('Y-m-d') ?>" 
                           style="padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.95rem;" required>
                    <input type="number" step="0.1" id="poidsInput" name="poids" placeholder="Votre poids (kg)" 
                           style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.95rem;" required>
                    <button type="submit" id="submitBtn" style="padding: 10px 20px; background: linear-gradient(90deg, #2f8f51, #4fbf7a); color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Enregistrer évolution</button>
                </form>
            </div>
        </div>
    </section>

    <div id="confirmModal" class="modal-overlay">
        <div class="modal-content">
            <h2 class="modal-title">Confirmer l'enregistrement</h2>
            <div class="modal-body">
                <p>Êtes-vous sûr(e) de vouloir enregistrer cette évolution ?</p>
                <p style="margin: 12px 0 0; color: #666; font-size: 0.9rem;" id="confirmDetails"></p>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" id="cancelBtn">Annuler</button>
                <button class="btn-confirm" id="confirmBtn">Confirmer</button>
            </div>
        </div>
    </div>
        </div>
    </section>

    <section id="stats" class="dashboard-section">
        <div style="background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 8px 24px rgba(14,20,30,0.06); border: 1px solid rgba(15,23,42,0.10);">
            <h3 style="margin: 0 0 24px; color: #2f8f51; font-size: 1.2rem;">📊 Statistiques</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 30px;">
                <div style="background: linear-gradient(135deg, rgba(47,143,81,0.1), rgba(79,191,122,0.1)); padding: 16px; border-radius: 10px; border-left: 4px solid #2f8f51;">
                    <div style="font-size: 0.85rem; color: #666; margin-bottom: 8px;">Poids Initial</div>
                    <div style="font-size: 1.8rem; font-weight: 700; color: #2f8f51;"><?= !empty($stats['poidsInitial']) ? $stats['poidsInitial'] : '-' ?> <span style="font-size: 0.9rem; color: #999;">kg</span></div>
                </div>
                <div style="background: linear-gradient(135deg, rgba(47,143,81,0.1), rgba(79,191,122,0.1)); padding: 16px; border-radius: 10px; border-left: 4px solid #2f8f51;">
                    <div style="font-size: 0.85rem; color: #666; margin-bottom: 8px;">Poids Actuel</div>
                    <div style="font-size: 1.8rem; font-weight: 700; color: #2f8f51;"><?= !empty($stats['poidsCurrent']) ? $stats['poidsCurrent'] : '-' ?> <span style="font-size: 0.9rem; color: #999;">kg</span></div>
                </div>
                <div style="background: linear-gradient(135deg, rgba(231,76,60,0.1), rgba(241,196,15,0.1)); padding: 16px; border-radius: 10px; border-left: 4px solid #e74c3c;">
                    <div style="font-size: 0.85rem; color: #666; margin-bottom: 8px;">Poids Perdu</div>
                    <div style="font-size: 1.8rem; font-weight: 700; color: #e74c3c;"><?= $stats['poidsPerte'] ?> <span style="font-size: 0.9rem; color: #999;">kg</span></div>
                </div>
            </div>


            <div style="background: #fafafa; padding: 16px; border-radius: 10px; margin-bottom: 30px;">
                <h4 style="margin: 0 0 16px; color: #333;">📊 Distribution IMC</h4>
                <div style="display: flex; align-items: center; gap: 40px;">
                    <div style="flex: 1; height: 300px; position: relative;">
                        <canvas id="imcDistributionChart"></canvas>
                    </div>
                    <div style="flex: 0 0 auto; text-align: center; padding: 20px; background: #fff; border-radius: 10px; border: 2px solid #ddd;">
                        <div style="font-size: 0.9rem; color: #666; margin-bottom: 12px;">Vous actuellement</div>
                        <div style="font-size: 3.5rem; font-weight: 800; margin-bottom: 12px; color: <?php 
                            $currentIMC = $stats['imcCurrent'] ?? 0;
                            echo ($currentIMC < 18.5) ? '#3498db' : (($currentIMC < 25) ? '#2f8f51' : (($currentIMC < 30) ? '#f39c12' : '#e74c3c'));
                        ?>;"><?= $currentIMC ?></div>
                        <div style="font-size: 0.85rem; padding: 8px 12px; border-radius: 6px; background: <?php 
                            $currentIMC = $stats['imcCurrent'] ?? 0;
                            echo ($currentIMC < 18.5) ? '#d6eaf8' : (($currentIMC < 25) ? '#e8f5e9' : (($currentIMC < 30) ? '#fff3e0' : '#ffebee'));
                        ?>; color: <?php 
                            $currentIMC = $stats['imcCurrent'] ?? 0;
                            echo ($currentIMC < 18.5) ? '#3498db' : (($currentIMC < 25) ? '#2f8f51' : (($currentIMC < 30) ? '#f39c12' : '#e74c3c'));
                        ?>; font-weight: 600;">
                            <?php 
                                $currentIMC = $stats['imcCurrent'] ?? 0;
                                if ($currentIMC < 18.5) {
                                    echo 'Maigreur (&lt;18.5)';
                                } elseif ($currentIMC < 25) {
                                    echo 'Normal (18.5-25)';
                                } elseif ($currentIMC < 30) {
                                    echo 'Surpoids (25-30)';
                                } else {
                                    echo 'Obésité (&gt;30)';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div style="background: #fafafa; padding: 16px; border-radius: 10px; margin-bottom: 30px;">
                <h4 style="margin: 0 0 16px; color: #333;">📊 Tableau Croisé Poids / Taille / IMC</h4>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem; min-width: 500px;">
                        <tr style="background: #f5f5f5; border-bottom: 2px solid #ddd;">
                            <th style="padding: 12px; text-align: left; font-weight: 600; color: #333;">Date</th>
                            <th style="padding: 12px; text-align: center; font-weight: 600; color: #333;">Poids (kg)</th>
                            <th style="padding: 12px; text-align: center; font-weight: 600; color: #333;">Taille (cm)</th>
                            <th style="padding: 12px; text-align: center; font-weight: 600; color: #333;">IMC</th>
                        </tr>
                        <?php foreach ($historique as $entry): ?>
                            <?php if ($entry['DateEvolution'] !== 'Poids initial'): ?>
                                <tr style="border-bottom: 1px solid #ddd;">
                                    <td style="padding: 12px; color: #555;"><?= htmlspecialchars($entry['DateEvolution']) ?></td>
                                    <td style="padding: 12px; text-align: center; color: #2f8f51; font-weight: 600;"><?= $entry['Poids'] ?></td>
                                    <td style="padding: 12px; text-align: center; color: #666;"><?= $currentTaille ?? '-' ?></td>
                                    <td style="padding: 12px; text-align: center; font-weight: 600; color: <?= ($entry['IMC'] ?? 0) < 25 ? '#2f8f51' : (($entry['IMC'] ?? 0) < 30 ? '#f39c12' : '#e74c3c') ?>;"><?= $entry['IMC'] ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>

            <div style="background: #fafafa; padding: 16px; border-radius: 10px;">
                <h4 style="margin: 0 0 16px; color: #333;">📅 Calendrier de Cure</h4>
                <?php if (!empty($currentRegime)): ?>
                    <div style="margin-bottom: 16px; padding: 12px; background: #e8f5e9; border-left: 4px solid #2f8f51; border-radius: 6px;">
                        <div style="font-weight: 600; color: #2f8f51; margin-bottom: 4px;">🍽️ Régime: <?= htmlspecialchars($currentRegime->RegimeNom) ?></div>
                        <div style="font-size: 0.9rem; color: #555;">
                            Début: <?= htmlspecialchars($currentRegime->DateDebut) ?> | Durée: <?= htmlspecialchars($currentRegime->DureeEnJours) ?> jours
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($currentSport)): ?>
                    <div style="margin-bottom: 16px; padding: 12px; background: #e3f2fd; border-left: 4px solid #4fbf7a; border-radius: 6px;">
                        <div style="font-weight: 600; color: #4fbf7a; margin-bottom: 4px;">⚽ Sport: <?= htmlspecialchars($currentSport->SportNom) ?></div>
                        <div style="font-size: 0.9rem; color: #555;">
                            Début: <?= htmlspecialchars($currentSport->DateDebut) ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div style="overflow-x: auto; padding-top: 16px;">
                    <div style="display: flex; gap: 8px; min-width: max-content;">
                        <?php 
                            $dateDebut = null;
                            $nbJours = 90;
                            
                            if (!empty($currentRegime)) {
                                $dateDebut = new DateTime($currentRegime->DateDebut);
                                $nbJours = (int) $currentRegime->DureeEnJours;
                            } else {
                                $dateDebut = new DateTime('now');
                                $dateDebut->modify('-30 days');
                            }
                            
                            $evolutionDates = array_map(function($e) { return $e['DateEvolution'] ?? null; }, $historique);
                            
                            for ($i = 0; $i < $nbJours; $i++) {
                                $currentDate = (clone $dateDebut)->modify("+{$i} days");
                                $dateStr = $currentDate->format('Y-m-d');
                                $hasEntry = in_array($dateStr, $evolutionDates);
                                $isToday = $dateStr === date('Y-m-d');
                        ?>
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 4px;">
                                <div style="width: 40px; height: 40px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.85rem; background: <?= $hasEntry ? '#4fbf7a' : '#f0f0f0' ?>; color: <?= $hasEntry ? '#fff' : '#999' ?>; border: <?= $isToday ? '2px solid #2f8f51' : 'none' ?>; position: relative;">
                                    <?= $hasEntry ? '✓' : $currentDate->format('d') ?>
                                </div>
                                <div style="font-size: 0.75rem; color: #999; text-align: center; width: 40px;">
                                    <?= $currentDate->format('M') ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>


</main>

<?php echo view('partials/Footer'); ?>

<script type="application/json" id="dashboardChartData"><?= json_encode([
    'labels' => array_column($historique ?? [], 'DateEvolution'),
    'weights' => array_column($historique ?? [], 'Poids'),
    'imcs' => array_column($historique ?? [], 'IMC'),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>
<script defer src="/assets/js/dashboard.js"></script>

</body>
</html>