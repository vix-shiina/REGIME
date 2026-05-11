document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.admin-card, .admin-stat-card');

    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(14px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';

        window.setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 90 * index);
    });
});
