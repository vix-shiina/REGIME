<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Ajouter du solde</title>
    <link rel="stylesheet" href="/assets/css/profile.css">
</head>
<body>
<?php $currentSolde = $currentSolde ?? 0; ?>

<?php echo view('partials/Header'); ?>

<main class="profile-page">
    <section class="profile-hero card">
        <div class="profile-hero-content">
            <h1>Ajouter du solde</h1>
            <p class="subtitle">Solde actuel : <?php echo number_format((float) $currentSolde, 2, ',', ' '); ?> Ar</p>
        </div>
        <div class="profile-actions">
            <a class="btn btn-secondary" href="/profil">Retour au profil</a>
        </div>
    </section>

    <?php echo view('partials/Flash'); ?>

    <section class="profile-stage">
        <section class="profile-panel is-active">
            <article class="card info-card">
                <div class="summary-head">
                    <div>
                        <p class="section-kicker">Code de recharge</p>
                        <h2>Entrer un code</h2>
                    </div>
                </div>

                <form class="profile-form" method="post" action="/profil/solde">
                    <div class="form-grid">
                        <label>
                            <span>Code</span>
                            <div style="display:flex;gap:12px;align-items:stretch;">
                                <input type="text" name="code" id="codeInput" placeholder="Ex. PROMO2026" required style="flex:1;">
                                <div id="codeAmountBox" style="min-width:140px;display:flex;align-items:center;justify-content:center;padding:0 14px;border-radius:14px;border:1px solid rgba(47,143,81,0.18);background:#f5faf4;color:#1f4d33;font-weight:800;white-space:nowrap;">
                                    0,00 Ar
                                </div>
                            </div>
                            <small id="codeStatus" style="display:block;margin-top:8px;color:#667085;"></small>
                        </label>
                    </div>

                    <div class="profile-footer-actions">
                        <button class="btn btn-primary" type="submit">Valider le code</button>
                    </div>
                </form>
            </article>
        </section>
    </section>
</main>

<?php echo view('partials/Footer'); ?>

<script>
(function () {
    const input = document.getElementById('codeInput');
    const amountBox = document.getElementById('codeAmountBox');
    const statusBox = document.getElementById('codeStatus');
    if (!input || !amountBox || !statusBox) return;

    let timer = null;
    let loadingTimer = null;

    const formatAmount = (value) => {
        const num = Number(value || 0);
        return new Intl.NumberFormat('fr-FR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(num) + ' Ar';
    };

    const setIdle = () => {
        amountBox.textContent = '0,00 Ar';
        statusBox.textContent = '';
        statusBox.style.color = '#667085';
    };

    const showLoading = () => {
        statusBox.innerHTML = '<span style="display:inline-flex;align-items:center;gap:8px;"><span style="width:10px;height:10px;border-radius:50%;border:2px solid #b8dd00;border-top-color:transparent;animation:spin 0.8s linear infinite;"></span> Vérification...</span>';
        statusBox.style.color = '#667085';
    };

    const finishLoading = () => {
        if (loadingTimer) {
            clearTimeout(loadingTimer);
            loadingTimer = null;
        }
        amountBox.style.opacity = '1';
    };

    const verify = async () => {
        const code = input.value.trim();
        if (!code) {
            setIdle();
            return;
        }

        amountBox.style.opacity = '0.75';
        showLoading();

        const startedAt = Date.now();

        try {
            const response = await fetch('/profil/solde/check', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'code=' + encodeURIComponent(code)
            });

            const data = await response.json();

            const elapsed = Date.now() - startedAt;
            const wait = Math.max(0, 1000 - elapsed);

            loadingTimer = setTimeout(() => {
                if (data && data.success) {
                    amountBox.textContent = formatAmount(data.amount);
                    statusBox.textContent = 'Code valide.';
                    statusBox.style.color = '#2f8f51';
                } else {
                    amountBox.textContent = '0,00 Ar';
                    statusBox.textContent = (data && data.message) ? data.message : 'Code invalide.';
                    statusBox.style.color = '#b42318';
                }
                finishLoading();
            }, wait);
        } catch (error) {
            const elapsed = Date.now() - startedAt;
            const wait = Math.max(0, 1000 - elapsed);

            loadingTimer = setTimeout(() => {
                amountBox.textContent = '0,00 Ar';
                statusBox.textContent = 'Erreur de vérification.';
                statusBox.style.color = '#b42318';
                finishLoading();
            }, wait);
        }
    };

    input.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(verify, 300);
    });
})();
</script>
<style>
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>
</body>
</html>