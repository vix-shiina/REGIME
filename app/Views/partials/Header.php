<link rel="stylesheet" href="/assets/css/header.css">
<script defer src="/assets/js/header.js"></script>

<header class="site-header">
    <div class="header-inner">
        <div class="brand">
            <a href="/index.php/"><img src="/assets/images/regime.png" alt="Logo" class="brand-logo"></a>
        </div>

        <nav class="main-nav" aria-label="Main navigation">
            <a class="nav-item" href="<?= site_url('myhome') ?>">
            <img src="/assets/images/icons/home.png" alt="Accueil" class="icon">
                <span>Accueil</span>
            </a>
            <a class="nav-item" href="<?= site_url('dashboard') ?>">

            <img src="/assets/images/icons/dashboard.png" alt="Dashboard" class="icon">
                <span>Dashboard</span>
            </a>
            <a class="nav-item account-link" href="<?= site_url('account') ?>" aria-label="Mon compte">
                <img src="/assets/images/icons/user.png" alt="Mon compte" class="icon">
                <span>Mon compte</span>
            </a>
        </nav>

        <div class="header-actions">
            <button class="contact-btn" id="contactBtn" aria-label="Contactez nous">
                <img src="/assets/images/icons/customer-service.png" alt="Contactez nous" class="icon">
                <span class="contact-text">Contactez nous</span>
                <span class="ring-dot" id="ringDot" aria-hidden="true"></span>
            </button>


        </div>
    </div>
</header>
