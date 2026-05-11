<link rel="stylesheet" href="/assets/css/header.css">
<script defer src="/assets/js/header.js"></script>

<?php
$session = session();
$uri = service('uri');
$currentPath = trim($uri->getPath(), '/');
$navMap = [
    'myhome' => 'myhome',
    'dashboard' => 'dashboard',
    'profil' => 'profil',
];
$activeNav = $navMap[$currentPath] ?? (str_starts_with($currentPath, 'profil') ? 'profil' : (str_starts_with($currentPath, 'dashboard') ? 'dashboard' : 'myhome'));
$activeIndex = $activeNav === 'dashboard' ? 0 : ($activeNav === 'myhome' ? 1 : 2);
$userBalance = null;

if (!empty($session->get('user_id'))) {
    try {
        $db = db_connect();
        $balanceRow = $db->table('UserSolde')
            ->select('Solde')
            ->where('UserId', (int) $session->get('user_id'))
            ->get()
            ->getRowArray();

        if (!empty($balanceRow)) {
            $userBalance = $balanceRow['Solde'];
        }
    } catch (\Throwable $e) {
        $userBalance = null;
    }
}
?>

<?php if ($activeNav === 'myhome'): ?>
    <div class="promo-ticker">
        <div class="promo-ticker-content">
            <span class="promo-ticker-item"> <img src="/assets/images/icons/coin.png" alt="Coin" class="icon"> Devenez client GOLD en payant en 1 fois, 15% de remise</span>
        
        </div>
    </div>
<?php endif; ?>

<header class="site-header">
    <div class="header-inner">
        <div class="brand">
            <a href="<?= site_url('myhome') ?>" class="brand-link" aria-label="regime.com">
                <img src="/assets/images/regime.png" alt="Logo" class="brand-logo">
                <span class="brand-name">regime.com</span>
            </a>
        </div>

        <nav class="main-nav" aria-label="Main navigation" style="--nav-index: <?= (int) $activeIndex ?>;">
            <a class="nav-item <?= $activeNav === 'dashboard' ? 'is-active' : '' ?>" href="<?= site_url('dashboard') ?>" data-nav-key="dashboard">
                <img src="/assets/images/icons/dashboard.png" alt="Dashboard" class="icon">
                <span>Dashboard</span>
            </a>
            <a class="nav-item <?= $activeNav === 'myhome' ? 'is-active' : '' ?>" href="<?= site_url('myhome') ?>" data-nav-key="myhome">
                <img src="/assets/images/icons/home.png" alt="Accueil" class="icon">
                <span>Accueil</span>
            </a>
            <a class="nav-item account-link <?= $activeNav === 'profil' ? 'is-active' : '' ?>" href="<?= site_url('profil') ?>" aria-label="Mon compte" data-nav-key="profil">
                <img src="/assets/images/icons/user.png" alt="Mon compte" class="icon">
                <span>Mon compte</span>
            </a>
        </nav>

        <div class="header-actions">
            <button class="contact-btn" id="contactBtn" aria-label="Contactez nous">
                <img src="/assets/images/icons/customer-service.png" alt="Contactez nous" class="icon">
                <span class="contact-text">Contactez nous</span>
                <span class="ring-dot ringing" id="ringDot" aria-hidden="true"></span>
            </button>
            <div class="user-balance" title="Solde de l'utilisateur">
                <img src="/assets/images/icons/coin.png" alt="Solde" class="icon">
                <span class="balance-label">Solde</span>
                <strong class="balance-value"><?php echo htmlspecialchars(number_format((float) $userBalance, 0, ',', ' ')) . ' Ar'; ?></strong>
            </div>


        </div>
    </div>
</header>


