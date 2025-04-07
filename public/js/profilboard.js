
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
    const ctx = document.getElementById('portfolioChart').getContext('2d');

    // Création d'un dégradé vertical pour le remplissage
    let gradientStroke = ctx.createLinearGradient(0, 0, 0, 300);
    gradientStroke.addColorStop(0, "rgba(241, 196, 15, 0.8)");
    gradientStroke.addColorStop(1, "rgba(241, 196, 15, 0.2)");

    if (!chart) {
        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Solde du Portefeuille',
                    data: dataSolde,
                    borderColor: "rgba(241, 196, 15, 1)",
                    backgroundColor: gradientStroke,
                    fill: true,
                    tension: 0.4,               // Courbes lisses
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: "rgba(241, 196, 15, 1)",
                    pointBorderColor: "#fff",
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: "rgba(241, 196, 15, 1)"
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        display: false,
                        grid: {
                            color: "rgba(255,255,255,0.1)"
                        },
                        ticks: {
                            color: "#F8F9FA",
                            font: { family: "'Poppins', sans-serif", size: 12 }
                        }
                    },
                    y: {
                        display: true,
                        grid: {
                            color: "rgba(255,255,255,0.1)"
                        },
                        ticks: {
                            color: "#F8F9FA",
                            font: { family: "'Poppins', sans-serif", size: 12 }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        backgroundColor: "rgba(0,0,0,0.7)",
                        titleFont: { family: "'Poppins', sans-serif", size: 14 },
                        bodyFont: { family: "'Poppins', sans-serif", size: 12 },
                        padding: 10,
                        cornerRadius: 8
                    },
                    legend: {
                        labels: {
                            font: { family: "'Roboto', sans-serif", size: 14 },
                            color: "#F8F9FA"
                        }
                    }
                }
            }
        });
    } else {
        chart.data.labels = labels;
        chart.data.datasets[0].data = dataSolde;
        chart.update();
    }
}

// ------------------ GESTION DES INTERVALLES ------------------
document.querySelectorAll('.interval-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        // 1. Supprime la classe "active" de tous les boutons
        document.querySelectorAll('.interval-btn').forEach(b => b.classList.remove('active'));

        // 2. Ajoute la classe "active" au bouton cliqué
        btn.classList.add('active');

        // 3. Met à jour l’intervalle actuel
        currentInterval = btn.getAttribute('data-interval');

        // 4. Rafraîchit les données
        refreshPortfolioData();
    });
});

// ------------------ INITIALISATION ------------------


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
document.addEventListener('DOMContentLoaded', () => {
    const defaultBtn = document.querySelector('.interval-btn[data-interval="jour"]');
    if (defaultBtn) {
        defaultBtn.classList.add('active');
    }
    refreshPortfolioData();
});
refreshProfilboardStats();
