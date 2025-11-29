const ctx1 = document.getElementById('usersPieChart');

new Chart(ctx1, {
    type: 'doughnut',
    data: {
        labels: rolesData.map(r => r.role),
        datasets: [{
            data: rolesData.map(r => r.total),
            backgroundColor: [
                'rgba(99, 102, 241, 0.8)', 'rgba(168, 85, 247, 0.8)', 'rgba(236, 72, 153, 0.8)', 'rgba(34, 197, 94, 0.8)'
            ],
        }]
    }
});

const ctx2 = document.getElementById('equipementsLineChart');

new Chart(ctx2, {
    type: 'line',
    data: {
        labels: evolutionData.map(e => e.annee),
        datasets: [{
            label: 'Nombre d\'équipements',
            data: evolutionData.map(e => e.total),
            borderWidth: 2,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
