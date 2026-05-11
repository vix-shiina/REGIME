document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('evolutionForm');
    const poidsInput = document.getElementById('poidsInput');
    const submitBtn = document.getElementById('submitBtn');
    const confirmModal = document.getElementById('confirmModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const confirmBtn = document.getElementById('confirmBtn');
    const confirmDetails = document.getElementById('confirmDetails');

    const chartDataEl = document.getElementById('dashboardChartData');
    const chartData = chartDataEl ? JSON.parse(chartDataEl.textContent || '{}') : {};

    const imcDistributionCanvas = document.getElementById('imcDistributionChart');
    if (imcDistributionCanvas && window.Chart) {
        const ctx = imcDistributionCanvas.getContext('2d');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Maigreur\n(<18.5)', 'Normal\n(18.5-25)', 'Surpoids\n(25-30)', 'Obésité\n(>30)'],
                datasets: [{
                    data: [25, 25, 25, 25],
                    backgroundColor: ['#3498db', '#2f8f51', '#f39c12', '#e74c3c'],
                    borderColor: '#fff',
                    borderWidth: 3,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { size: 12 },
                            usePointStyle: true,
                            padding: 20,
                        },
                    },
                    tooltip: {
                        enabled: true,
                        callbacks: {
                            label(context) {
                                return context.label.replace(/\n/g, ' ');
                            },
                        },
                    },
                },
            },
        });
    }

    if (form && poidsInput && submitBtn) {
        function updateButtonState() {
            const isEmpty = !poidsInput.value || poidsInput.value.trim() === '';
            submitBtn.disabled = isEmpty;
        }

        poidsInput.addEventListener('input', updateButtonState);
        poidsInput.addEventListener('change', updateButtonState);
        updateButtonState();

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            if (!confirmModal || !confirmDetails) {
                form.submit();
                return;
            }

            const dateInput = form.querySelector('input[name="dateEvolution"]');
            const poids = poidsInput.value;
            const date = dateInput && dateInput.value ? new Date(dateInput.value).toLocaleDateString('fr-FR') : '-';

            confirmDetails.textContent = `Poids: ${poids} kg | Date: ${date}`;
            confirmModal.classList.add('active');
        });
    }

    if (cancelBtn && confirmModal) {
        cancelBtn.addEventListener('click', function () {
            confirmModal.classList.remove('active');
        });
    }

    if (confirmBtn && confirmModal && form) {
        confirmBtn.addEventListener('click', function () {
            confirmModal.classList.remove('active');
            form.submit();
        });
    }

    if (confirmModal) {
        confirmModal.addEventListener('click', function (e) {
            if (e.target === confirmModal) {
                confirmModal.classList.remove('active');
            }
        });
    }

    const canvasElement = document.getElementById('evolutionChart');
    if (canvasElement && window.Chart) {
        const ctx = canvasElement.getContext('2d');
        const labels = Array.isArray(chartData.labels) ? chartData.labels : [];
        const weights = Array.isArray(chartData.weights) ? chartData.weights : [];
        const imcs = Array.isArray(chartData.imcs) ? chartData.imcs : [];

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
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
                        pointBorderWidth: 2,
                        yAxisID: 'y',
                    },
                    {
                        label: 'IMC',
                        data: imcs,
                        borderColor: '#e74c3c',
                        backgroundColor: 'rgba(231, 76, 60, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 5,
                        pointBackgroundColor: '#e74c3c',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        yAxisID: 'y1',
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: { display: true, position: 'top' },
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        min: 0,
                        max: 150,
                        grid: { color: 'rgba(47, 143, 81, 0.05)' },
                        title: { display: true, text: 'Poids (kg)' },
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        min: 0,
                        max: 50,
                        grid: { drawOnChartArea: false },
                        title: { display: true, text: 'IMC' },
                    },
                    x: {
                        grid: { display: false },
                    },
                },
            },
        });
    }
});
