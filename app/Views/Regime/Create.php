<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Commencer votre régime</title>
    <link rel="stylesheet" href="/assets/css/regime.css">
    <script defer src="/assets/js/regime.js"></script>
</head>
<body>
<?php
$user = $user ?? [];
$sports = $sports ?? [];
?>

<?php echo view('partials/Header'); ?>
<?php echo view('partials/Flash'); ?>

<main class="regime-page wizard-page">
    <section class="wizard-shell card">
        <header class="wizard-hero">
            <p class="eyebrow">Mon régime</p>
            <h1>Commencer votre régime</h1>
        </header>

        <div class="form-alert" id="wizardAlert" hidden></div>

        <form id="regimeForm" class="wizard-form" method="post" action="/regime/create">
            <input type="hidden" name="mode" id="modeInput" value="">
            <input type="hidden" name="duration_months" id="durationMonthsHidden" value="">
            <input type="hidden" name="selected_regime_id" id="selectedRegimeId" value="">
            <input type="hidden" name="sport_id" id="selectedSportId" value="">
            <input type="hidden" name="sport_frequency" id="selectedSportFrequency" value="0">
            <input type="hidden" name="target_unit" id="targetUnitHidden" value="">
            <input type="hidden" name="target_value" id="targetValueHidden" value="">
            <input type="hidden" name="payment_type" id="paymentTypeHidden" value="">

            <!-- ÉTAPE 0 (pas comptée) : Poids + Taille -->
            <section class="wizard-panel is-visible" data-step="0">
                <div class="question-card">
                    <p class="question-label">Informations de base</p>
                    <h2>Entrez votre poids et votre taille</h2>
                    <p class="question-help">On calcule l'IMC directement ici, sans passer par une autre page.</p>

                    <div class="field-grid two-cols compact">
                        <label>
                            <span>Poids actuel (kg)</span>
                            <?php if (!empty($user['Poids'])): ?>
                                <select id="weightSelect" disabled>
                                    <option value="<?php echo htmlspecialchars((string)$user['Poids']); ?>"><?php echo htmlspecialchars((string)$user['Poids']); ?> kg (profil)</option>
                                </select>
                                <input type="hidden" name="weight" id="weightInput" value="<?php echo htmlspecialchars((string)$user['Poids']); ?>">
                            <?php else: ?>
                                <input type="number" step="0.1" min="0" name="weight" id="weightInput" value="" placeholder="Ex. 62.5">
                            <?php endif; ?>
                        </label>
                        <label>
                            <span>Taille actuelle (cm)</span>
                            <?php if (!empty($user['Taille'])): ?>
                                <select id="heightSelect" disabled>
                                    <option value="<?php echo htmlspecialchars((string)$user['Taille']); ?>"><?php echo htmlspecialchars((string)$user['Taille']); ?> cm (profil)</option>
                                </select>
                                <input type="hidden" name="height" id="heightInput" value="<?php echo htmlspecialchars((string)$user['Taille']); ?>">
                            <?php else: ?>
                                <input type="number" step="0.1" min="0" name="height" id="heightInput" value="" placeholder="Ex. 170">
                            <?php endif; ?>
                        </label>
                    </div>

                    <div class="action-row">
                        <button type="button" class="submit-btn" id="calcBtn">Calculer l'IMC</button>
                    </div>
                </div>
            </section>

            <!-- ÉTAPE 0.5 (pas comptée) : Afficher IMC + Choix Mode -->
            <section class="wizard-panel" data-step="0.5" hidden>
                <div class="question-card">
                    <p class="question-label">Choix du mode</p>
                    <h2>Votre IMC est calculé</h2>
                    <p class="question-help">Que souhaitez-vous faire ?</p>

                    <div class="imc-banner" style="margin-bottom:20px;padding:10px;border-radius:8px;background:#f7f9fb;border:1px solid #e6eef6">
                        <p style="margin:0">IMC : <strong id="imcValue2">-</strong> — <span id="imcZone2">-</span></p>
                    </div>

                    <div class="field-grid" style="display:flex;gap:12px;margin-bottom:16px">
                        <label style="display:flex;align-items:center;gap:6px;padding:10px 14px;border:2px solid #e6eef6;border-radius:6px;cursor:pointer;transition:all 0.2s;flex:1;font-size:0.95em">
                            <input type="radio" name="modeChoice" value="perso" id="modePerso" style="margin:0;width:18px;height:18px;cursor:pointer">
                            <span>Créer mon régime</span>
                        </label>
                        <label style="display:flex;align-items:center;gap:6px;padding:10px 14px;border:2px solid #e6eef6;border-radius:6px;cursor:pointer;transition:all 0.2s;flex:1;font-size:0.95em">
                            <input type="radio" name="modeChoice" value="conseil" id="modeConseil" style="margin:0;width:18px;height:18px;cursor:pointer">
                            <span>Conseillez moi</span>
                        </label>
                    </div>

                    <div class="action-row">
                        <button type="button" class="back-btn" data-go-step="0">Retour</button>
                        <button type="button" class="submit-btn" id="validateModeBtn" disabled>Valider</button>
                    </div>
                </div>
            </section>

            <!-- ÉTAPE 1 PERSO : Entrer poids/IMC cible -->
            <section class="wizard-panel" data-step="perso-1" hidden>
                <div class="question-card">
                    <p class="question-label">Étape 1 — Régime personnalisé</p>
                    <h2>Quel est votre objectif ?</h2>
                    <p class="question-help">Choisissez un poids cible ou un IMC cible.</p>

                    <div class="field-grid two-cols">
                        <label>
                            <span>Type d'objectif</span>
                            <div style="display:flex;gap:12px;margin-top:8px">
                                <label style="display:flex;align-items:center;gap:6px;padding:10px 14px;border:2px solid #e6eef6;border-radius:6px;cursor:pointer;transition:all 0.2s;flex:1;font-size:0.95em">
                                    <input type="radio" name="persoTargetUnit" value="bmi" id="persoTargetUnitBmi" checked style="margin:0;width:18px;height:18px;cursor:pointer">
                                    <span>IMC cible</span>
                                </label>
                                <label style="display:flex;align-items:center;gap:6px;padding:10px 14px;border:2px solid #e6eef6;border-radius:6px;cursor:pointer;transition:all 0.2s;flex:1;font-size:0.95em">
                                    <input type="radio" name="persoTargetUnit" value="weight" id="persoTargetUnitWeight" style="margin:0;width:18px;height:18px;cursor:pointer">
                                    <span>Poids cible (kg)</span>
                                </label>
                            </div>
                        </label>
                        <label>
                            <span id="persoTargetValueLabelText">IMC cible</span>
                            <input type="number" step="0.1" min="10" id="persoTargetValue" placeholder="Ex. 22.0">
                        </label>
                    </div>

                    <div class="action-row">
                        <button type="button" class="back-btn" data-go-step="0.5">Retour</button>
                        <button type="button" class="submit-btn" data-go-step="perso-2">Continuer</button>
                    </div>
                </div>
            </section>

            <!-- ÉTAPE 1 CONSEIL : En combien de temps -->
            <section class="wizard-panel" data-step="conseil-1" hidden>
                <div class="question-card">
                    <p class="question-label">Étape 1 — Régime conseillé</p>
                    <h2>En combien de temps souhaitez-vous faire le traitement ?</h2>
                    <p class="question-help">Indiquez la durée souhaitée.</p>

                    <div class="field-grid">
                        <label>
                            <span>Choisir l'unité</span>
                            <div class="duration-buttons">
                                <label class="duration-btn">
                                    <input type="radio" name="conseilDurationUnit" value="months" id="conseilDurationMonthsRadio">
                                    <span>Mois</span>
                                </label>
                                <label class="duration-btn">
                                    <input type="radio" name="conseilDurationUnit" value="weeks" id="conseilDurationWeeksRadio">
                                    <span>Semaines</span>
                                </label>
                            </div>
                        </label>
                        <label id="conseilDurationValueLabel" style="display:none">
                            <span id="conseilDurationValueLabelText">Valeur</span>
                            <input type="number" min="1" step="0.5" id="conseilDurationValue" placeholder="Ex. 3">
                        </label>
                    </div>

                    <div class="action-row">
                        <button type="button" class="back-btn" data-go-step="0.5">Retour</button>
                        <button type="button" id="conseilContinueBtn" class="submit-btn" data-go-step="conseil-2" disabled>Continuer</button>
                    </div>
                </div>
            </section>

            <!-- ÉTAPE 2 PERSO : En combien de temps -->
            <section class="wizard-panel" data-step="perso-2" hidden>
                <div class="question-card">
                    <p class="question-label">Étape 2 — Régime personnalisé</p>
                    <h2>En combien de temps souhaitez-vous faire le traitement ?</h2>
                    <p class="question-help">Indiquez la durée souhaitée.</p>

                    <div class="field-grid">
                        <label>
                            <span>Choisir l'unité</span>
                            <div class="duration-buttons">
                                <label class="duration-btn">
                                    <input type="radio" name="persoDurationUnit" value="months" id="persoDurationMonthsRadio">
                                    <span>Mois</span>
                                </label>
                                <label class="duration-btn">
                                    <input type="radio" name="persoDurationUnit" value="weeks" id="persoDurationWeeksRadio">
                                    <span>Semaines</span>
                                </label>
                            </div>
                        </label>
                        <label id="persoDurationValueLabel" style="display:none">
                            <span id="persoDurationValueLabelText">Valeur</span>
                            <input type="number" min="1" step="0.5" id="persoDurationValue" placeholder="Ex. 3">
                        </label>
                    </div>

                    <div class="action-row">
                        <button type="button" class="back-btn" data-go-step="perso-1">Retour</button>
                        <button type="button" id="persoContinueBtn" class="submit-btn" data-go-step="perso-3" disabled>Continuer</button>
                    </div>
                </div>
            </section>

            <!-- ÉTAPE 2 CONSEIL : Sport ? -->
            <section class="wizard-panel" data-step="conseil-2" hidden>
                <div class="question-card">
                    <p class="question-label">Étape 2 — Régime conseillé</p>
                    <h2>Souhaitez-vous faire du sport ?</h2>
                    <p class="question-help">Cochez oui si vous souhaitez ajouter une activité physique.</p>

                    <div class="field-grid">
                        <label>
                            <span>Ajouter du sport</span>
                            <div class="duration-buttons">
                                <label class="duration-btn">
                                    <input type="radio" name="conseilAddSport" value="0" id="conseilAddSportNo">
                                    <span>Non</span>
                                </label>
                                <label class="duration-btn">
                                    <input type="radio" name="conseilAddSport" value="1" id="conseilAddSportYes">
                                    <span>Oui</span>
                                </label>
                            </div>
                        </label>
                        <label id="conseilFreqLabel" style="display:none">
                            <span>Fréquence (par semaine)</span>
                            <input type="range" min="0" max="7" step="1" id="conseilFreqRange" value="0">
                        </label>
                    </div>

                    <div class="action-row">
                        <button type="button" class="back-btn" data-go-step="conseil-1">Retour</button>
                        <button type="button" class="submit-btn" data-go-step="conseil-3">Continuer</button>
                    </div>
                </div>
            </section>

            <!-- ÉTAPE 3 PERSO : Sport ? -->
            <section class="wizard-panel" data-step="perso-3" hidden>
                <div class="question-card">
                    <p class="question-label">Étape 3 — Régime personnalisé</p>
                    <h2>Souhaitez-vous faire du sport ?</h2>
                    <p class="question-help">Cochez oui si vous souhaitez ajouter une activité physique.</p>

                    <div class="field-grid">
                        <label>
                            <span>Ajouter du sport</span>
                            <div class="duration-buttons">
                                <label class="duration-btn">
                                    <input type="radio" name="persoAddSport" value="0" id="persoAddSportNo">
                                    <span>Non</span>
                                </label>
                                <label class="duration-btn">
                                    <input type="radio" name="persoAddSport" value="1" id="persoAddSportYes">
                                    <span>Oui</span>
                                </label>
                            </div>
                        </label>
                        <label id="persoFreqLabel" style="display:none">
                            <span>Fréquence (par semaine)</span>
                            <input type="range" min="0" max="7" step="1" id="persoFreqRange" value="0">
                        </label>
                    </div>

                    <div class="action-row">
                        <button type="button" class="back-btn" data-go-step="perso-2">Retour</button>
                        <button type="button" class="submit-btn" data-go-step="perso-4">Continuer</button>
                    </div>
                </div>
            </section>

            <!-- ÉTAPE 3 CONSEIL : Résumé -->
            <section class="wizard-panel" data-step="conseil-3" hidden>
                <div class="question-card">
                    <p class="question-label">Étape 3 — Régime conseillé</p>
                    <h2>Résumé de vos choix</h2>
                    <p class="question-help">Voici ce que vous avez sélectionné.</p>

                    <div class="preview-box" id="conseilResumeBox">
                        <p class="muted">Résumé des choix (à remplir).</p>
                    </div>

                    <div class="action-row">
                        <button type="button" class="back-btn" data-go-step="conseil-2">Retour</button>
                        <button type="button" class="submit-btn" data-go-step="conseil-4">Continuer</button>
                    </div>
                </div>
            </section>

            <!-- ÉTAPE 4 PERSO : Résumé -->
            <section class="wizard-panel" data-step="perso-4" hidden>
                <div class="question-card">
                    <p class="question-label">Étape 4 — Régime personnalisé</p>
                    <h2>Résumé de vos choix</h2>
                    <p class="question-help">Voici ce que vous avez sélectionné.</p>

                    <div class="preview-box" id="persoResumeBox">
                        <p class="muted">Résumé des choix (à remplir).</p>
                    </div>

                    <div class="action-row">
                        <button type="button" class="back-btn" data-go-step="perso-3">Retour</button>
                        <button type="button" class="submit-btn" data-go-step="perso-5">Continuer</button>
                    </div>
                </div>
            </section>

            <!-- ÉTAPE 4 CONSEIL : Suggestion régime + sport + valider/annuler -->
            <section class="wizard-panel" data-step="conseil-4" hidden>
                <div class="question-card">
                    <p class="question-label">Étape 4 — Régime conseillé</p>
                    <h2>Voici votre suggestion</h2>
                    <p class="question-help">Régime et sport recommandés suite à notre algorithme.</p>

                    <div class="preview-box" id="conseilSuggestionBox">
                        <p class="muted">Suggestion du régime et du sport (à remplir).</p>
                    </div>

                    <div class="action-row">
                        <button type="button" class="back-btn" data-go-step="conseil-3">Retour</button>
                        <button type="button" class="submit-btn" data-go-step="conseil-5">Valider</button>
                        <button type="button" class="back-btn secondary" data-go-step="0.5">Annuler</button>
                    </div>
                </div>
            </section>

            <!-- ÉTAPE 5 PERSO : Suggestion régime + sport + valider/annuler -->
            <section class="wizard-panel" data-step="perso-5" hidden>
                <div class="question-card">
                    <p class="question-label">Étape 5 — Régime personnalisé</p>
                    <h2>Voici votre suggestion</h2>
                    <p class="question-help">Régime et sport recommandés suite à notre algorithme.</p>

                    <div class="preview-box" id="persoSuggestionBox">
                        <p class="muted">Suggestion du régime et du sport (à remplir).</p>
                    </div>

                    <div class="action-row">
                        <button type="button" class="back-btn" data-go-step="perso-4">Retour</button>
                        <button type="button" class="submit-btn" data-go-step="perso-6">Valider</button>
                        <button type="button" class="back-btn secondary" data-go-step="0.5">Annuler</button>
                    </div>
                </div>
            </section>

            <!-- ÉTAPE 5 CONSEIL : Paiement -->
            <section class="wizard-panel" data-step="conseil-5" hidden>
                <div class="question-card">
                    <p class="question-label">Étape 5 — Régime conseillé</p>
                    <h2>Procéder au paiement</h2>
                    <p class="question-help">Choisissez votre option de paiement.</p>

                    <div class="field-grid two-cols">
                        <label>
                            <span>Mode de paiement</span>
                            <select id="conseilPaymentSelect">
                                <option value="Paiement unique">Payer en 1 fois (Client Gold)</option>
                                <option value="Abonnement">S'abonner (Plusieurs fois)</option>
                            </select>
                        </label>
                    </div>

                    <div class="action-row">
                        <button type="button" class="back-btn" data-go-step="conseil-4">Retour</button>
                        <button class="submit-btn" type="submit" id="conseilSubmitBtn">Procéder au paiement</button>
                    </div>
                </div>
            </section>

            <!-- ÉTAPE 6 PERSO : Paiement -->
            <section class="wizard-panel" data-step="perso-6" hidden>
                <div class="question-card">
                    <p class="question-label">Étape 6 — Régime personnalisé</p>
                    <h2>Procéder au paiement</h2>
                    <p class="question-help">Choisissez votre option de paiement.</p>

                    <div class="field-grid two-cols">
                        <label>
                            <span>Mode de paiement</span>
                            <select id="persoPaymentSelect">
                                <option value="Paiement unique">Payer en 1 fois (Client Gold)</option>
                                <option value="Abonnement">S'abonner (Plusieurs fois)</option>
                            </select>
                        </label>
                    </div>

                    <div class="action-row">
                        <button type="button" class="back-btn" data-go-step="perso-5">Retour</button>
                        <button class="submit-btn" type="submit" id="persoSubmitBtn">Procéder au paiement</button>
                    </div>
                </div>
            </section>

        </form>
    </section>
</main>

<?php echo view('partials/Footer'); ?>

<script type="application/json" id="regimeInitialData">
<?php echo json_encode([
    'weight' => $user['Poids'] ?? null,
    'height' => $user['Taille'] ?? null,
    'regimes' => $regimes ?? [],
    'sports' => $sports ?? [],
    'initialPreview' => $initialPreview ?? null,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
</script>

</body>
</html>
