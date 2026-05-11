(() => {
    // Step manager: supports numeric and string step ids
    // Navigation via data-go-step buttons and dot clicks
    // IMC calculator + sport frequency toggle

    try {
        const panels = Array.from(document.querySelectorAll('.wizard-panel'));
        const dots = Array.from(document.querySelectorAll('.wizard-step'));

        const weightInput = document.getElementById('weightInput');
        const heightInput = document.getElementById('heightInput');
        const calcBtn = document.getElementById('calcBtn');
        const imcValue = document.getElementById('imcValue');
        const imcZone = document.getElementById('imcZone');
        const imcAdvice = document.getElementById('imcAdvice');
        const imcValue2 = document.getElementById('imcValue2');
        const imcZone2 = document.getElementById('imcZone2');

        function showStep(step) {
            // Hide all panels
            panels.forEach(p => {
                const s = p.getAttribute('data-step');
                const visible = String(s) === String(step);
                p.hidden = !visible;
                p.classList.toggle('is-visible', visible);
            });

            // Update dots (only for numeric steps 1-6, not 0 or 0.5)
            const numericStep = parseInt(step);
            const isDotStep = !isNaN(numericStep) && numericStep >= 1 && numericStep <= 6;
            
            if (isDotStep) {
                dots.forEach(d => d.classList.toggle('is-active', d.getAttribute('data-step-dot') === String(numericStep)));
            } else {
                dots.forEach(d => d.classList.remove('is-active'));
            }
            // Populate resume boxes when showing summary steps and compute suggestions
            try {
                if (String(step) === 'conseil-3') populateConseilResume();
                if (String(step) === 'perso-4') populatePersoResume();
                if (String(step) === 'conseil-4') computeConseilSuggestion();
                if (String(step) === 'perso-5') computePersoSuggestion();
                if (String(step) === '0.5') updateModeChoiceDisplay();
            } catch (err) {
                // ignore resume/suggestion errors
                console.warn('resume/suggestion populate error', err);
            }
        }

        function computeBmi() {
            const w = weightInput ? parseFloat(weightInput.value) : NaN;
            const h = heightInput ? parseFloat(heightInput.value) : NaN;
            if (!w || !h || isNaN(w) || isNaN(h) || h <= 0) return null;
            const m = h / 100;
            const bmi = w / (m * m);
            return Math.round(bmi * 10) / 10;
        }

        // Calc IMC button
        if (calcBtn) {
            calcBtn.addEventListener('click', () => {
                const bmi = computeBmi();
                if (bmi === null) {
                    alert('Veuillez saisir un poids et une taille valides.');
                    return;
                }
                // Update both IMC displays
                if (imcValue) imcValue.textContent = bmi;
                if (imcZone) imcZone.textContent = (bmi < 18.5) ? 'Maigreur' : (bmi < 25) ? 'Normal' : (bmi < 30) ? 'Surpoids' : 'Obésité';
                if (imcValue2) imcValue2.textContent = bmi;
                if (imcZone2) imcZone2.textContent = (bmi < 18.5) ? 'Maigreur' : (bmi < 25) ? 'Normal' : (bmi < 30) ? 'Surpoids' : 'Obésité';
                if (imcAdvice) imcAdvice.textContent = 'IMC calculé — choisissez la suite.';
                // Move to choice step
                showStep('0.5');
            });
        }

        // Mode selection via buttons used by the current wizard
        const modeInput = document.getElementById('modeInput');
        const createModeBtn = document.getElementById('createModeBtn');
        const adviseModeBtn = document.getElementById('adviseModeBtn');

        function setWizardMode(mode) {
            if (modeInput) modeInput.value = mode;
            if (createModeBtn) createModeBtn.classList.toggle('active', mode === 'custom');
            if (adviseModeBtn) adviseModeBtn.classList.toggle('active', mode === 'suggested');
        }

        if (createModeBtn) {
            createModeBtn.addEventListener('click', () => {
                setWizardMode('custom');
                showStep('perso-1');
            });
        }

        if (adviseModeBtn) {
            adviseModeBtn.addEventListener('click', () => {
                setWizardMode('suggested');
                showStep('conseil-1');
            });
        }

        // Handle mode choice radio buttons (new wizard UI)
        const modeChoiceRadios = document.querySelectorAll('input[name="modeChoice"]');
        const validateModeBtn = document.getElementById('validateModeBtn');
        
        function updateModeChoiceDisplay() {
            const selected = Array.from(modeChoiceRadios).find(r => r.checked);
            if (validateModeBtn) {
                validateModeBtn.disabled = !selected;
            }
        }
        
        modeChoiceRadios.forEach(radio => {
            radio.addEventListener('change', updateModeChoiceDisplay);
        });
        updateModeChoiceDisplay();

        // Handle validateModeBtn click - navigate to the selected mode's first step
        if (validateModeBtn) {
            validateModeBtn.addEventListener('click', () => {
                const selected = Array.from(modeChoiceRadios).find(r => r.checked);
                if (selected) {
                    if (selected.value === 'perso') {
                        setWizardMode('custom');
                        showStep('perso-1');
                    } else if (selected.value === 'conseil') {
                        setWizardMode('suggested');
                        showStep('conseil-1');
                    }
                }
            });
        }

        setWizardMode('custom');

        // Generic navigation for elements with data-go-step
        document.querySelectorAll('[data-go-step]').forEach(btn => btn.addEventListener('click', () => {
            const dest = btn.getAttribute('data-go-step');
            if (dest) showStep(dest);
        }));

        // Allow clicking dots for quick testing (only 1-6)
        dots.forEach(dot => dot.addEventListener('click', () => {
            const dotNum = dot.getAttribute('data-step-dot');
            showStep(dotNum);
        }));

        // Prevent Enter key in inputs from submitting the form and navigating away
        document.querySelectorAll('#regimeForm input').forEach(inp => {
            inp.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    return false;
                }
            });
        });
        // Duration unit toggles (radio buttons - show/hide value input based on selection)
        
        // Conseil branch
        const conseilDurationRadios = document.querySelectorAll('input[name="conseilDurationUnit"]');
        const conseilDurationValueLabel = document.getElementById('conseilDurationValueLabel');
        const conseilDurationValueLabelText = document.getElementById('conseilDurationValueLabelText');
        const conseilDurationValue = document.getElementById('conseilDurationValue');
        const conseilContinueBtn = document.getElementById('conseilContinueBtn');
        
        function updateConseilDurationDisplay() {
            const selected = Array.from(conseilDurationRadios).find(r => r.checked);
            if (conseilDurationValueLabel) {
                conseilDurationValueLabel.style.display = selected ? '' : 'none';
                if (selected && conseilDurationValueLabelText) {
                    if (selected.value === 'months') {
                        conseilDurationValueLabelText.textContent = 'Nombre de mois';
                    } else if (selected.value === 'weeks') {
                        conseilDurationValueLabelText.textContent = 'Nombre de semaines';
                    }
                }
                // enable continue only when a valid number is entered
                if (conseilContinueBtn) {
                    const val = conseilDurationValue ? parseFloat(conseilDurationValue.value) : NaN;
                    conseilContinueBtn.disabled = !(selected && !isNaN(val) && val > 0);
                }
                if (durationMonthsHidden) {
                    const val = conseilDurationValue ? parseFloat(conseilDurationValue.value) : NaN;
                    durationMonthsHidden.value = selected && !isNaN(val) && val > 0
                        ? (selected.value === 'weeks' ? String(Math.round((val / 4) * 100) / 100) : String(val))
                        : '';
                }
            }
        }
        
        conseilDurationRadios.forEach(radio => {
            radio.addEventListener('change', updateConseilDurationDisplay);
        });
        if (conseilDurationValue) conseilDurationValue.addEventListener('input', updateConseilDurationDisplay);

        // Perso branch
        const persoDurationRadios = document.querySelectorAll('input[name="persoDurationUnit"]');
        const persoDurationValueLabel = document.getElementById('persoDurationValueLabel');
        const persoDurationValueLabelText = document.getElementById('persoDurationValueLabelText');
        const persoDurationValue = document.getElementById('persoDurationValue');
        const persoContinueBtn = document.getElementById('persoContinueBtn');
        const persoTargetUnitRadios = document.querySelectorAll('input[name="persoTargetUnit"]');
        const persoTargetValue = document.getElementById('persoTargetValue');
        const persoTargetValueLabelText = document.getElementById('persoTargetValueLabelText');

        function getPersoTargetUnit() {
            const selected = Array.from(persoTargetUnitRadios).find(r => r.checked);
            if (selected?.value) return selected.value;
            return document.getElementById('persoTargetUnit')?.value || '';
        }

        function updatePersoTargetDisplay() {
            const unit = getPersoTargetUnit();
            if (!persoTargetValue) return;

            if (unit === 'weight') {
                if (persoTargetValueLabelText) persoTargetValueLabelText.textContent = 'Poids cible (kg)';
                persoTargetValue.placeholder = 'Ex. 65';
                persoTargetValue.min = '20';
                persoTargetValue.max = '350';
                persoTargetValue.step = '0.1';
            } else {
                if (persoTargetValueLabelText) persoTargetValueLabelText.textContent = 'IMC cible';
                persoTargetValue.placeholder = 'Ex. 22.0';
                persoTargetValue.min = '10';
                persoTargetValue.max = '60';
                persoTargetValue.step = '0.1';
            }
        }
        
        function updatePersonalDurationDisplay() {
            const selected = Array.from(persoDurationRadios).find(r => r.checked);
            if (persoDurationValueLabel) {
                persoDurationValueLabel.style.display = selected ? '' : 'none';
                if (selected && persoDurationValueLabelText) {
                    if (selected.value === 'weeks') {
                        persoDurationValueLabelText.textContent = 'Nombre de semaines';
                    } else if (selected.value === 'months') {
                        persoDurationValueLabelText.textContent = 'Nombre de mois';
                    }
                }
                if (persoContinueBtn) {
                    const val = persoDurationValue ? parseFloat(persoDurationValue.value) : NaN;
                    persoContinueBtn.disabled = !(selected && !isNaN(val) && val > 0);
                }
                if (durationMonthsHidden) {
                    const val = persoDurationValue ? parseFloat(persoDurationValue.value) : NaN;
                    durationMonthsHidden.value = selected && !isNaN(val) && val > 0
                        ? (selected.value === 'weeks' ? String(Math.round((val / 4) * 100) / 100) : String(val))
                        : '';
                }
            }
        }
        
        persoDurationRadios.forEach(radio => {
            radio.addEventListener('change', updatePersonalDurationDisplay);
        });
        if (persoDurationValue) persoDurationValue.addEventListener('input', updatePersonalDurationDisplay);
        persoTargetUnitRadios.forEach(radio => {
            radio.addEventListener('change', updatePersoTargetDisplay);
        });
        updatePersoTargetDisplay();

        // Build resume content for conseil
        function populateConseilResume() {
            const box = document.getElementById('conseilResumeBox');
            if (!box) return;
            // Duration
            const durSelected = Array.from(conseilDurationRadios).find(r => r.checked);
            const durVal = document.getElementById('conseilDurationValue')?.value || '';
            const duration = durSelected ? (durVal ? (`${durVal} ${durSelected.value === 'months' ? 'mois' : 'semaines'}`) : (`-- ${durSelected.value}`)) : 'Non précisé';
            // Sport
            const sportSel = Array.from(conseilAddSportRadios).find(r => r.checked);
            const sport = sportSel ? (sportSel.value === '1' ? `Oui — ${document.getElementById('conseilFreqRange')?.value || 0} fois/semaine` : 'Non') : 'Non précisé';

            box.innerHTML = `
                <div class="preview-state">
                    <div><strong>Durée choisie :</strong> <span>${duration}</span></div>
                    <div><strong>Activité physique :</strong> <span>${sport}</span></div>
                </div>
            `;
        }

        // Build resume content for perso
        function populatePersoResume() {
            const box = document.getElementById('persoResumeBox');
            if (!box) return;
            const targetUnit = getPersoTargetUnit();
            const targetVal = document.getElementById('persoTargetValue')?.value || '';
            const target = targetUnit ? (targetVal ? `${targetVal} ${targetUnit === 'weight' ? 'kg' : 'IMC'}` : `-- ${targetUnit}`) : 'Non précisé';

            const durSelected = Array.from(persoDurationRadios).find(r => r.checked);
            const durVal = document.getElementById('persoDurationValue')?.value || '';
            const duration = durSelected ? (durVal ? (`${durVal} ${durSelected.value === 'months' ? 'mois' : 'semaines'}`) : (`-- ${durSelected.value}`)) : 'Non précisé';

            const sportSel = Array.from(persoAddSportRadios).find(r => r.checked);
            const sport = sportSel ? (sportSel.value === '1' ? `Oui — ${document.getElementById('persoFreqRange')?.value || 0} fois/semaine` : 'Non') : 'Non précisé';

            box.innerHTML = `
                <div class="preview-state">
                    <div><strong>Objectif :</strong> <span>${target}</span></div>
                    <div><strong>Durée choisie :</strong> <span>${duration}</span></div>
                    <div><strong>Activité physique :</strong> <span>${sport}</span></div>
                </div>
            `;
        }

        // Helper: read numeric value or return NaN
        function toNum(v) {
            const n = parseFloat(v);
            return isNaN(n) ? NaN : n;
        }

        // Robust getters that tolerate older markup (inputs/selects) or newer radios
        function getConseilDurationMonths() {
            // Try radio-based controls first
            const radios = document.querySelectorAll('input[name="conseilDurationUnit"]');
            if (radios && radios.length) {
                const sel = Array.from(radios).find(r => r.checked);
                const val = document.getElementById('conseilDurationValue')?.value;
                if (sel && val) {
                    if (sel.value === 'months') return toNum(val);
                    if (sel.value === 'weeks') return toNum(val) / 4;
                }
            }
            // Fallback: older fields
            const m = document.getElementById('conseilDurationMonths')?.value;
            const w = document.getElementById('conseilDurationWeeks')?.value;
            const d = document.getElementById('conseilDurationDays')?.value;
            if (m) return toNum(m);
            if (w) return toNum(w) / 4;
            if (d) return toNum(d) / 30;
            return NaN;
        }

        function getPersoDurationMonths() {
            const radios = document.querySelectorAll('input[name="persoDurationUnit"]');
            if (radios && radios.length) {
                const sel = Array.from(radios).find(r => r.checked);
                const val = document.getElementById('persoDurationValue')?.value;
                if (sel && val) {
                    if (sel.value === 'months') return toNum(val);
                    if (sel.value === 'weeks') return toNum(val) / 4;
                }
            }
            const m = document.getElementById('persoDurationMonths')?.value;
            const w = document.getElementById('persoDurationWeeks')?.value;
            if (m) return toNum(m);
            if (w) return toNum(w) / 4;
            return NaN;
        }

        function getSportChoice(prefix) {
            // prefix: 'conseil' or 'perso'
            const radios = document.querySelectorAll(`input[name="${prefix}AddSport"]`);
            if (radios && radios.length) {
                const sel = Array.from(radios).find(r => r.checked);
                if (sel) return { add: sel.value === '1', freq: toNum(document.getElementById(prefix + 'FreqRange')?.value) || 0 };
            }
            const sel = document.getElementById(prefix + 'AddSport');
            if (sel) {
                const add = sel.value === '1' || sel.value === 'Oui' || sel.value === 'yes';
                return { add, freq: toNum(document.getElementById(prefix + 'FreqRange')?.value) || 0 };
            }
            return { add: false, freq: 0 };
        }

        const selectedRegimeIdInput = document.getElementById('selectedRegimeId');
        const selectedSportIdInput = document.getElementById('selectedSportId');
        const selectedSportFrequencyInput = document.getElementById('selectedSportFrequency');
        const targetUnitHidden = document.getElementById('targetUnitHidden');
        const targetValueHidden = document.getElementById('targetValueHidden');

        function syncSelectedRegime(regime) {
            if (selectedRegimeIdInput) selectedRegimeIdInput.value = regime?.Id ?? '';
        }

        function syncSelectedSport(sport, frequency, enabled) {
            if (!enabled || !sport) {
                if (selectedSportIdInput) selectedSportIdInput.value = '';
                if (selectedSportFrequencyInput) selectedSportFrequencyInput.value = '0';
                return;
            }
            if (selectedSportIdInput) selectedSportIdInput.value = sport?.Id ?? '';
            if (selectedSportFrequencyInput) selectedSportFrequencyInput.value = String(Math.max(0, frequency || 0));
        }

        function syncCustomTarget(unit, value) {
            if (targetUnitHidden) targetUnitHidden.value = unit || '';
            if (targetValueHidden) targetValueHidden.value = value || '';
        }

        function getInitialWizardData() {
            const dataEl = document.getElementById('regimeInitialData');
            if (!dataEl || !dataEl.textContent) return { regimes: [], sports: [] };
            try {
                return JSON.parse(dataEl.textContent) || { regimes: [], sports: [] };
            } catch (err) {
                return { regimes: [], sports: [] };
            }
        }

        function pickField(obj, keys, fallback = undefined) {
            for (const key of keys) {
                if (obj && obj[key] !== undefined && obj[key] !== null && obj[key] !== '') return obj[key];
            }
            return fallback;
        }

        function regimeTypeToGoal(typeId) {
            const t = String(typeId ?? '').trim().toLowerCase();
            if (t === '1' || t.includes('prise') || t.includes('gain') || t.includes('masse')) return 'gain';
            if (t === '2' || t.includes('perte') || t.includes('loss') || t.includes('minceur')) return 'loss';
            return 'maintain';
        }

        function sportTypeToGoal(typeId) {
            const t = String(typeId ?? '').trim().toLowerCase();
            if (t === '1' || t.includes('prise') || t.includes('masse')) return 'gain';
            if (t === '2' || t.includes('perte') || t.includes('weight')) return 'loss';
            if (t === '3' || t.includes('maintien') || t.includes('forme')) return 'maintain';
            return 'maintain';
        }

        function formatMoney(value) {
            const n = toNum(value);
            if (isNaN(n)) return 'Non précisé';
            return new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 0, maximumFractionDigits: 2 }).format(n);
        }

        function formatDelta(value) {
            const n = toNum(value);
            if (isNaN(n)) return 'Non précisé';
            const abs = Math.abs(n);
            const sign = n > 0 ? '+' : (n < 0 ? '-' : '');
            return `${sign}${abs.toFixed(1)}`;
        }

        function estimateImc(weight, heightCm) {

        function capTheoreticalDelta(delta, currentWeight) {
            const d = toNum(delta);
            const w = toNum(currentWeight);
            if (isNaN(d)) return NaN;
            if (isNaN(w) || w <= 0) return d;

            const maxChange = Math.max(3, w * 0.25);
            return Math.max(-maxChange, Math.min(maxChange, d));
        }
            const w = toNum(weight);
            const h = toNum(heightCm);
            if (isNaN(w) || isNaN(h) || h <= 0) return NaN;
            const m = h / 100;
            return w / (m * m);
        }

        function defaultWeeklyFreq(goal) {
            if (goal === 'gain') return 4;
            if (goal === 'loss') return 5;
            return 3;
        }

        function goalTargetBmi(goal) {
            if (goal === 'gain') return 21.0;
            if (goal === 'loss') return 22.5;
            return 22.0;
        }

        function chooseRegimeForGoal(goal, regimes) {
            if (!regimes.length) {
                return { regime: null, adapted: false };
            }

            const targeted = regimes.filter(r => {
                const regimeType = pickField(r, ['TypeDeRegimeId', 'type_id', 'typeId'], pickField(r, ['TypeDeRegime', 'type', 'typeLabel']));
                return regimeTypeToGoal(regimeType) === goal;
            });

            const pool = targeted.length ? targeted : regimes;
            const regime = pool.reduce((best, item) => {
                const currentEfficacy = toNum(pickField(item, ['EfficacitePoidsParSemaine', 'efficacite', 'efficiency'])) || 0;
                const bestEfficacy = best ? (toNum(pickField(best, ['EfficacitePoidsParSemaine', 'efficacite', 'efficiency'])) || 0) : -1;
                if (!best) return item;
                return currentEfficacy > bestEfficacy ? item : best;
            }, null);

            return {
                regime,
                adapted: targeted.length > 0,
            };
        }

        function estimateDurationWeeks(deltaKg, weeklyImpact) {
            const delta = Math.abs(toNum(deltaKg));
            const impact = Math.max(0.1, toNum(weeklyImpact));
            if (isNaN(delta) || delta <= 0) return 0;
            return Math.max(1, delta / impact);
        }

        function formatRegimeDetails(regime, durationMonths) {
            const name = pickField(regime, ['RegimeNom', 'name', 'title', 'label'], 'Régime recommandé');
            const typeId = pickField(regime, ['TypeDeRegimeId', 'type_id', 'typeId']);
            const typeLabel = pickField(regime, ['TypeDeRegime', 'type', 'typeLabel']);
            const price = pickField(regime, ['PrixJournaliere', 'daily_price', 'price_daily', 'price']);
            const efficacy = pickField(regime, ['EfficacitePoidsParSemaine', 'efficacite', 'efficiency']);
            return {
                name,
                typeId,
                typeLabel,
                goal: regimeTypeToGoal(typeId ?? typeLabel),
                price,
                efficacy,
                durationMonths,
            };
        }

        function formatSportDetails(sport, weeklyFreq) {
            const name = pickField(sport, ['SportNom', 'name', 'title', 'label'], 'Sport recommandé');
            const typeId = pickField(sport, ['TypeDeSportId', 'type_id', 'typeId']);
            const typeLabel = pickField(sport, ['TypeDeSport', 'type', 'typeLabel']);
            const efficacy = pickField(sport, ['EfficacitePoidsParSceance', 'efficacite', 'efficiency']);
            return {
                name,
                typeId,
                typeLabel,
                goal: sportTypeToGoal(typeId ?? typeLabel),
                efficacy,
                weeklyFreq,
            };
        }

        function renderRegimeHtml(regimeInfo) {
            return `
                <section class="suggestion-section">
                    <h4>Régime recommandé</h4>
                    <div class="suggestion-card">
                    <h3>${regimeInfo.name}</h3>
                    <ul>
                        <li><strong>Type :</strong> ${regimeInfo.typeLabel ?? regimeInfo.typeId ?? 'Non précisé'}</li>
                        <li><strong>Objectif :</strong> ${regimeInfo.goal === 'gain' ? 'Prise de poids' : regimeInfo.goal === 'loss' ? 'Perte de poids' : 'Maintien'}</li>
                        <li><strong>Prix journalier :</strong> ${formatMoney(regimeInfo.price)} Ar</li>
                        <li><strong>Efficacité / semaine :</strong> ${regimeInfo.efficacy ?? 'Non précisé'}</li>
                        <li><strong>Durée :</strong> ${regimeInfo.durationMonths ? `${regimeInfo.durationMonths} mois` : 'À définir selon votre critère'}</li>
                    </ul>
                    </div>
                </section>
            `;
        }

        function renderSportHtml(sportInfo) {
            return `
                <section class="suggestion-section">
                    <h4>Sport recommandé</h4>
                    <div class="suggestion-card">
                    <h3>${sportInfo.name}</h3>
                    <ul>
                        <li><strong>Type :</strong> ${sportInfo.typeLabel ?? sportInfo.typeId ?? 'Non précisé'}</li>
                        <li><strong>Objectif :</strong> ${sportInfo.goal === 'gain' ? 'Prise de masse' : sportInfo.goal === 'loss' ? 'Perte de poids' : 'Maintien de la forme'}</li>
                        <li><strong>Efficacité / séance :</strong> ${sportInfo.efficacy ?? 'Non précisé'}</li>
                        <li><strong>Fréquence hebdo :</strong> ${sportInfo.weeklyFreq} séance(s)/semaine</li>
                    </ul>
                    </div>
                </section>
            `;
        }

        function renderNoSportHtml() {
            return `
                <section class="suggestion-section">
                    <h4>Sport</h4>
                    <div class="suggestion-card">
                        <h3>Aucun sport</h3>
                        <p>Vous avez choisi de ne pas ajouter d'activité physique.</p>
                    </div>
                </section>
            `;
        }

        function renderSummaryHtml(items) {
            return `
                <section class="suggestion-summary">
                    <h4>Résumé rapide</h4>
                    <div class="summary-grid">
                        ${items.map(item => `
                            <div class="summary-item">
                                <span>${item.label}</span>
                                <strong>${item.value}</strong>
                            </div>
                        `).join('')}
                    </div>
                </section>
            `;
        }

        function renderTheoryHtml(items) {
            return `
                <section class="suggestion-section suggestion-section-full">
                    <h4>Théorique</h4>
                    <div class="suggestion-card">
                        <ul>
                            ${items.map(item => `<li><strong>${item.label} :</strong> ${item.value}</li>`).join('')}
                        </ul>
                    </div>
                </section>
            `;
        }

        function renderDetailsHtml(items) {
            return `
                <section class="suggestion-section suggestion-section-full">
                    <h4>Détails</h4>
                    <div class="suggestion-card">
                        <ul>
                            ${items.map(item => `<li><strong>${item.label} :</strong> ${item.value}</li>`).join('')}
                        </ul>
                    </div>
                </section>
            `;
        }

        function selectBestSport(goal, sports) {
            if (!sports.length) return null;
            const targeted = sports.filter(s => {
                const sportType = pickField(s, ['TypeDeSportId', 'type_id', 'typeId'], pickField(s, ['TypeDeSport', 'type', 'typeLabel']));
                return sportTypeToGoal(sportType) === goal;
            });
            const pool = targeted.length ? targeted : sports;
            return pool.reduce((best, sport) => {
                const currentEfficacy = toNum(pickField(sport, ['EfficacitePoidsParSceance', 'efficacite', 'efficiency'])) || 0;
                const bestEfficacy = best ? (toNum(pickField(best, ['EfficacitePoidsParSceance', 'efficacite', 'efficiency'])) || 0) : -1;
                if (!best) return sport;
                return currentEfficacy > bestEfficacy ? sport : best;
            }, null);
        }

        // Suggestion algorithm for 'conseil' mode (simple, explainable rules)
        function computeConseilSuggestion() {
            const box = document.getElementById('conseilSuggestionBox');
            if (!box) return;
            const initial = getInitialWizardData();
            const regimes = initial.regimes || [];
            const sports = initial.sports || [];

            const weight = toNum(document.getElementById('weightInput')?.value);
            const height = toNum(document.getElementById('heightInput')?.value);
            const bmi = estimateImc(weight, height) || toNum(imcValue2?.textContent);
            const months = getConseilDurationMonths();
            const sport = getSportChoice('conseil');

            let goal = 'maintain';
            if (!isNaN(bmi)) {
                if (bmi < 18.5) goal = 'gain';
                else if (bmi >= 25) goal = 'loss';
            }

            const regimeChoice = chooseRegimeForGoal(goal, regimes);
            const bestRegime = regimeChoice.regime;
            const selectedRegime = bestRegime ? formatRegimeDetails(bestRegime, isNaN(months) ? null : months) : null;
            const bestSport = sport.add ? selectBestSport(goal, sports) : null;
            const sportInfo = sport.add ? formatSportDetails(bestSport || {}, sport.freq || defaultWeeklyFreq(goal)) : null;
            const durationWeeks = isNaN(months) ? 0 : months * 4.33;
            const regimeWeeklyImpact = toNum(pickField(bestRegime || {}, ['EfficacitePoidsParSemaine', 'efficacite', 'efficiency'])) || 0;
            const sportWeeklyImpact = sport.add ? ((toNum(pickField(bestSport || {}, ['EfficacitePoidsParSceance', 'efficacite', 'efficiency'])) || 0) * (sport.freq || defaultWeeklyFreq(goal)) * 0.15) : 0;
            const theoreticalImpact = (regimeWeeklyImpact + sportWeeklyImpact) * durationWeeks;
            const rawDelta = goal === 'gain' ? theoreticalImpact : goal === 'loss' ? -theoreticalImpact : 0;
            const theoreticalDelta = capTheoreticalDelta(rawDelta, weight);
            const projectedWeight = !isNaN(weight) ? Math.max(0, weight + theoreticalDelta) : NaN;
            const projectedImc = estimateImc(projectedWeight, height);
            const currentWeightLabel = isNaN(weight) ? 'Non précisé' : `${weight.toFixed(1)} kg`;
            const currentImcLabel = isNaN(bmi) ? 'Non précisé' : bmi.toFixed(1);
            const projectedWeightLabel = isNaN(projectedWeight) ? 'Non précisé' : `${projectedWeight.toFixed(1)} kg`;
            const projectedImcLabel = isNaN(projectedImc) ? 'Non précisé' : projectedImc.toFixed(1);
            const theoryLabel = goal === 'gain' ? `Gain théorique de ${formatDelta(theoreticalDelta)} kg` : goal === 'loss' ? `Perte théorique de ${formatDelta(theoreticalDelta)} kg` : 'Évolution théorique nulle';
            const adaptedNotice = regimeChoice.adapted
                ? ''
                : `<div class="suggestion-summary" style="margin-top:14px;background:#fff8e6;border:1px solid rgba(243,156,18,.22)"><h4>Aucune cure adaptée</h4><p>Nous n'avons pas trouvé de cure correspondant exactement à vos critères. Voici une alternative avec une durée conseillée pour atteindre votre objectif.</p></div>`;
            const alternativeDuration = estimateDurationWeeks(
                goal === 'gain' ? Math.max(0, (goalTargetBmi(goal) * ((height / 100) ** 2)) - weight) : Math.max(0, weight - (goalTargetBmi(goal) * ((height / 100) ** 2))),
                regimeWeeklyImpact + sportWeeklyImpact
            );

            syncSelectedRegime(bestRegime);
            syncSelectedSport(bestSport, sport.freq || defaultWeeklyFreq(goal), sport.add);

            if (!selectedRegime) {
                box.innerHTML = `
                    <div class="suggestion-result">
                        <h3>Aucune base régime disponible</h3>
                        <p>Les données du tableau des régimes ne sont pas disponibles.</p>
                        ${renderSportHtml(sportInfo)}
                    </div>
                `;
                return;
            }
            box.innerHTML = `
                <div class="suggestion-result">
                    <h3>Régime conseillé</h3>
                    <p>Voici le régime retenu depuis la base selon votre IMC et vos critères.</p>
                    ${adaptedNotice}
                    ${renderSummaryHtml([
                        { label: 'Objectif', value: goal === 'gain' ? 'Prise de poids' : goal === 'loss' ? 'Perte de poids' : 'Maintien' },
                        { label: 'Durée', value: isNaN(months) ? 'Non précisée' : `${months} mois` },
                        { label: 'Sport', value: sport.add ? 'Oui' : 'Non' },
                    ])}
                    <div class="suggestion-sections">
                        ${renderRegimeHtml(selectedRegime)}
                        ${sport.add ? renderSportHtml(sportInfo) : renderNoSportHtml()}
                        ${renderTheoryHtml([
                            { label: 'Poids actuel', value: currentWeightLabel },
                            { label: 'IMC actuel', value: currentImcLabel },
                            { label: 'Projection poids', value: projectedWeightLabel },
                            { label: 'Projection IMC', value: projectedImcLabel },
                            { label: 'Résultat théorique', value: theoryLabel },
                            { label: 'Durée conseillée si aucune cure adaptée', value: `${alternativeDuration.toFixed(1)} semaine(s)` },
                            { label: 'Fréquence hebdo', value: sport.add ? `${sportInfo.weeklyFreq} séance(s)/semaine` : '0 séance/semaine' },
                        ])}
                    </div>
                </div>
            `;
        }

        // Suggestion algorithm for 'perso' mode (target-driven)
        function computePersoSuggestion() {
            const box = document.getElementById('persoSuggestionBox');
            if (!box) return;
            const initial = getInitialWizardData();
            const regimes = initial.regimes || [];
            const sports = initial.sports || [];

            const currentWeight = toNum(document.getElementById('weightInput')?.value);
            const height = toNum(document.getElementById('heightInput')?.value);
            const unit = getPersoTargetUnit();
            const targetVal = toNum(document.getElementById('persoTargetValue')?.value);
            let targetKg = NaN;
            if (unit === 'weight' && !isNaN(targetVal)) targetKg = targetVal;
            else if (unit === 'bmi' && !isNaN(targetVal) && !isNaN(height)) {
                const m = height / 100;
                targetKg = targetVal * (m * m);
            }

            const months = getPersoDurationMonths();
            const sport = getSportChoice('perso');
            const goal = !isNaN(currentWeight) && !isNaN(targetKg)
                ? (currentWeight > targetKg ? 'loss' : currentWeight < targetKg ? 'gain' : 'maintain')
                : 'maintain';

            syncCustomTarget(unit, targetVal);

            const regimeChoice = chooseRegimeForGoal(goal, regimes);
            const bestRegime = regimeChoice.regime;
            const selectedRegime = bestRegime ? formatRegimeDetails(bestRegime, isNaN(months) ? null : months) : null;
            const bestSport = sport.add ? selectBestSport(goal, sports) : null;
            const sportInfo = sport.add ? formatSportDetails(bestSport || {}, sport.freq || defaultWeeklyFreq(goal)) : null;
            const durationWeeks = isNaN(months) ? 0 : months * 4.33;
            const regimeWeeklyImpact = toNum(pickField(bestRegime || {}, ['EfficacitePoidsParSemaine', 'efficacite', 'efficiency'])) || 0;
            const sportWeeklyImpact = sport.add ? ((toNum(pickField(bestSport || {}, ['EfficacitePoidsParSceance', 'efficacite', 'efficiency'])) || 0) * (sport.freq || defaultWeeklyFreq(goal)) * 0.15) : 0;
            const theoreticalImpact = (regimeWeeklyImpact + sportWeeklyImpact) * durationWeeks;
            const rawDelta = goal === 'gain' ? theoreticalImpact : goal === 'loss' ? -theoreticalImpact : 0;
            const theoreticalDelta = capTheoreticalDelta(rawDelta, currentWeight);
            const projectedWeight = !isNaN(currentWeight) ? Math.max(0, currentWeight + theoreticalDelta) : (isNaN(targetKg) ? NaN : targetKg);
            const currentImc = estimateImc(currentWeight, height);
            const projectedImc = estimateImc(projectedWeight, height);
            const currentWeightLabel = isNaN(currentWeight) ? 'Non précisé' : `${currentWeight.toFixed(1)} kg`;
            const currentImcLabel = isNaN(currentImc) ? 'Non précisé' : currentImc.toFixed(1);
            const projectedWeightLabel = isNaN(projectedWeight) ? 'Non précisé' : `${projectedWeight.toFixed(1)} kg`;
            const projectedImcLabel = isNaN(projectedImc) ? 'Non précisé' : projectedImc.toFixed(1);
            const theoryLabel = goal === 'gain' ? `Gain théorique de ${formatDelta(theoreticalDelta)} kg` : goal === 'loss' ? `Perte théorique de ${formatDelta(theoreticalDelta)} kg` : 'Évolution théorique nulle';
            const adaptedNotice = regimeChoice.adapted
                ? ''
                : `<div class="suggestion-summary" style="margin-top:14px;background:#fff8e6;border:1px solid rgba(243,156,18,.22)"><h4>Aucune cure adaptée</h4><p>Nous n'avons pas trouvé de cure correspondant exactement à vos critères. Voici une alternative avec une durée conseillée pour atteindre votre objectif.</p></div>`;
            const targetReferenceWeight = !isNaN(targetKg) ? targetKg : (goal === 'gain' ? Math.max(currentWeight, currentWeight + Math.abs(theoreticalDelta)) : Math.max(0, currentWeight - Math.abs(theoreticalDelta)));
            const alternativeDuration = estimateDurationWeeks(
                !isNaN(currentWeight) && !isNaN(targetReferenceWeight) ? Math.abs(targetReferenceWeight - currentWeight) : 0,
                regimeWeeklyImpact + sportWeeklyImpact
            );

            syncSelectedRegime(bestRegime);
            syncSelectedSport(bestSport, sport.freq || defaultWeeklyFreq(goal), sport.add);

            if (!selectedRegime) {
                box.innerHTML = `
                    <div class="suggestion-result">
                        <h3>Aucune base régime disponible</h3>
                        <p>Les données du tableau des régimes ne sont pas disponibles.</p>
                        ${renderSportHtml(sportInfo)}
                    </div>
                `;
                return;
            }

            box.innerHTML = `
                <div class="suggestion-result">
                    <h3>Régime personnalisé conseillé</h3>
                    <p>Régime sélectionné depuis la base selon votre objectif, votre durée et votre poids actuel.</p>
                    ${adaptedNotice}
                    ${renderSummaryHtml([
                        { label: 'Poids actuel', value: currentWeightLabel },
                        { label: 'Objectif cible', value: unit === 'weight' ? (isNaN(targetVal) ? 'Non précisé' : `${targetVal} kg`) : isNaN(targetVal) ? 'Non précisé' : `IMC ${targetVal}` },
                        { label: 'Durée', value: isNaN(months) ? 'Non précisée' : `${months} mois` },
                    ])}
                    <div class="suggestion-sections">
                        ${renderRegimeHtml(selectedRegime)}
                        ${sport.add ? renderSportHtml(sportInfo) : renderNoSportHtml()}
                        ${renderTheoryHtml([
                            { label: 'IMC actuel', value: currentImcLabel },
                            { label: 'Poids actuel', value: currentWeightLabel },
                            { label: 'IMC fin de cure', value: projectedImcLabel },
                            { label: 'Poids fin de cure', value: projectedWeightLabel },
                            { label: 'Résultat théorique', value: theoryLabel },
                            { label: 'Durée conseillée si aucune cure adaptée', value: `${alternativeDuration.toFixed(1)} semaine(s)` },
                            { label: 'Fréquence hebdo', value: sport.add ? `${sportInfo.weeklyFreq} séance(s)/semaine` : '0 séance/semaine' },
                        ])}
                    </div>
                </div>
            `;
        }


        // Sport frequency toggles
        // Conseil branch
        const conseilFreqLabel = document.getElementById('conseilFreqLabel');
        const conseilFreqValue = document.getElementById('conseilFreqValue');
        
        const conseilAddSportRadios = document.querySelectorAll('input[name="conseilAddSport"]');
        function updateConseilSportDisplay() {
            const selected = Array.from(conseilAddSportRadios).find(r => r.checked);
            if (conseilFreqLabel) {
                conseilFreqLabel.style.display = selected && selected.value === '1' ? '' : 'none';
            }
            if (selectedSportFrequencyInput) {
                selectedSportFrequencyInput.value = selected && selected.value === '1'
                    ? String(document.getElementById('conseilFreqRange')?.value || 0)
                    : '0';
            }
            if (selectedSportIdInput && (!selected || selected.value !== '1')) {
                selectedSportIdInput.value = '';
            }
        }
        conseilAddSportRadios.forEach(radio => {
            radio.addEventListener('change', updateConseilSportDisplay);
        });
        const conseilFreqRange = document.getElementById('conseilFreqRange');
        if (conseilFreqRange) {
            if (!conseilFreqRange.value) conseilFreqRange.value = '1';
            if (conseilFreqValue) conseilFreqValue.textContent = conseilFreqRange.value;
            conseilFreqRange.addEventListener('input', () => {
                if (conseilFreqValue) conseilFreqValue.textContent = conseilFreqRange.value;
                if (selectedSportFrequencyInput) selectedSportFrequencyInput.value = String(conseilFreqRange.value || 0);
            });
        }

        // Perso branch
        const persoFreqLabel = document.getElementById('persoFreqLabel');
        const persoFreqValue = document.getElementById('persoFreqValue');
        
        const persoAddSportRadios = document.querySelectorAll('input[name="persoAddSport"]');
        function updatePersoSportDisplay() {
            const selected = Array.from(persoAddSportRadios).find(r => r.checked);
            if (persoFreqLabel) {
                persoFreqLabel.style.display = selected && selected.value === '1' ? '' : 'none';
            }
            if (selectedSportFrequencyInput) {
                selectedSportFrequencyInput.value = selected && selected.value === '1'
                    ? String(document.getElementById('persoFreqRange')?.value || 0)
                    : '0';
            }
            if (selectedSportIdInput && (!selected || selected.value !== '1')) {
                selectedSportIdInput.value = '';
            }
        }
        persoAddSportRadios.forEach(radio => {
            radio.addEventListener('change', updatePersoSportDisplay);
        });
        const persoFreqRange = document.getElementById('persoFreqRange');
        if (persoFreqRange) {
            if (!persoFreqRange.value) persoFreqRange.value = '1';
            if (persoFreqValue) persoFreqValue.textContent = persoFreqRange.value;
            persoFreqRange.addEventListener('input', () => {
                if (persoFreqValue) persoFreqValue.textContent = persoFreqRange.value;
                if (selectedSportFrequencyInput) selectedSportFrequencyInput.value = String(persoFreqRange.value || 0);
            });
        }

        // Sync payment select values with hidden input before form submission
        const paymentTypeHidden = document.getElementById('paymentTypeHidden');
        const conseilPaymentSelect = document.getElementById('conseilPaymentSelect');
        const persoPaymentSelect = document.getElementById('persoPaymentSelect');
        const regimeForm = document.getElementById('regimeForm');

        if (regimeForm) {
            regimeForm.addEventListener('submit', (e) => {
                // Determine which payment select is visible
                if (conseilPaymentSelect && !document.querySelector('[data-step="conseil-5"]')?.hidden) {
                    if (paymentTypeHidden) paymentTypeHidden.value = conseilPaymentSelect.value || '';
                } else if (persoPaymentSelect && !document.querySelector('[data-step="perso-6"]')?.hidden) {
                    if (paymentTypeHidden) paymentTypeHidden.value = persoPaymentSelect.value || '';
                }
            });
        }

        // Start at step 0
        showStep('0');

    } catch (err) {
        console.error('regime.js error:', err);
    }
})();