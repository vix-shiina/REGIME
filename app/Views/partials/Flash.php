<?php
$flashSuccess = session()->getFlashdata('flash_success');
$flashError = session()->getFlashdata('flash_error');
?>

<?php if (!empty($flashSuccess)): ?>
    <div class="toast success flash-toast"><?php echo htmlspecialchars($flashSuccess); ?></div>
<?php endif; ?>

<?php if (!empty($flashError)): ?>
    <div class="toast error flash-toast"><?php echo htmlspecialchars($flashError); ?></div>
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
