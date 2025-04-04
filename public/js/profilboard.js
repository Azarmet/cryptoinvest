
// ------------------ PORTFOLIO SECTION ------------------
let currentInterval = 'jour';

// Rafraîchissement du portefeuille (graphique, stats, valeur actuelle et solde disponible)
function refreshPortfolioData() {
    fetch(`index.php?page=profilboard&action=refreshPortfolioData&pseudo=${pseudoleaderboard}&interval=${currentInterval}`)
    .then(res => res.json())
    .then(data => {
        updatePortfolioChart(data.chartData);

        // Sélection des éléments
        const roiElement = document.getElementById('roi-total');
        const pnlElement = document.getElementById('pnl-total');

        // Mise à jour du contenu
        roiElement.textContent = data.stats.roiTotal + ' %';
        pnlElement.textContent = data.stats.pnlTotal + ' USDT';

        // Réinitialisation des classes
        roiElement.classList.remove('positive', 'negative');
        pnlElement.classList.remove('positive', 'negative');

        // Ajout des classes en fonction des valeurs
        if (parseFloat(data.stats.roiTotal) >= 0) {
            roiElement.classList.add('positive');
        } else {
            roiElement.classList.add('negative');
        }

        if (parseFloat(data.stats.pnlTotal) >= 0) {
            pnlElement.classList.add('positive');
        } else {
            pnlElement.classList.add('negative');
        }

        document.getElementById('tx-count').textContent = data.stats.txCount;
        document.getElementById('current-portfolio-value').textContent = "Valeur actuelle : " + data.currentValue + " USDT";
    })
    .catch(err => console.error(err));
}


// Gestion du graphique via Chart.js
let chart;
function updatePortfolioChart(chartData) {
    const labels = chartData.map(d => d.date);
    const dataSolde = chartData.map(d => d.solde);

    if (!chart) {
        const ctx = document.getElementById('portfolioChart').getContext('2d');
        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Solde du Portefeuille',
                    data: dataSolde,
                    borderColor: 'blue',
                    fill: false
                }]
            },
            options: {
                responsive: false,
                scales: {
                    x: { display: true },
                    y: { display: true }
                }
            }
        });
    } else {
        chart.data.labels = labels;
        chart.data.datasets[0].data = dataSolde;
        chart.update();
    }
}

// Changement d'intervalle
document.querySelectorAll('.interval-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        currentInterval = btn.getAttribute('data-interval');
        refreshPortfolioData();
    });
});

function refreshProfilboardStats() {
    fetch(`index.php?page=profilboard&action=getStats&pseudo=${pseudoleaderboard}`)
        .then(res => res.json())
        .then(stats => {
            document.getElementById("stat-gagnantes").textContent = stats.gagnantes;
            document.getElementById("stat-perdantes").textContent = stats.perdantes;
            document.getElementById("stat-long").textContent = stats.total_long;
            document.getElementById("stat-long-win").textContent = stats.longs_gagnants;
            document.getElementById("stat-short").textContent = stats.total_short;
            document.getElementById("stat-short-win").textContent = stats.shorts_gagnants;
            document.getElementById("stat-pnl-moyen").textContent = parseFloat(stats.pnl_moyen).toFixed(2);
            document.getElementById("stat-temps-moyen").textContent = parseFloat(stats.temps_moyen_heures).toFixed(1);
            document.getElementById("stat-tx-mois").textContent = parseFloat(stats.tx_par_mois).toFixed(2);
        })
        .catch(err => console.error(err));
}
// ------------------ INITIALISATION ------------------
refreshProfilboardStats();
refreshPortfolioData();
