<?php
$flashSuccess = session()->getFlashdata('flash_success');
$flashError = session()->getFlashdata('flash_error');
?>

<?php if (!empty($flashSuccess)): ?>
    <div class="toast success flash-toast" style="position:fixed;right:18px;bottom:18px;z-index:9999;padding:10px 14px;border-radius:6px;color:#fff;font-size:13px;box-shadow:0 6px 14px rgba(0,0,0,0.12);background:#28a745;max-width:min(420px,calc(100vw - 36px));word-break:break-word;">
        <?php echo htmlspecialchars($flashSuccess); ?>
    </div>
<?php endif; ?>

<?php if (!empty($flashError)): ?>
    <div class="toast error flash-toast" style="position:fixed;right:18px;bottom:18px;z-index:9999;padding:10px 14px;border-radius:6px;color:#fff;font-size:13px;box-shadow:0 6px 14px rgba(0,0,0,0.12);background:#dc3545;max-width:min(420px,calc(100vw - 36px));word-break:break-word;">
        <?php echo htmlspecialchars($flashError); ?>
    </div>
<?php endif; ?>

<script>
(function () {
    const toasts = document.querySelectorAll('.flash-toast');
    if (!toasts.length) return;

    window.setTimeout(() => {
        toasts.forEach((toast) => {
            toast.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(8px)';
            window.setTimeout(() => toast.remove(), 260);
        });
    }, 3000);
})();
</script>
