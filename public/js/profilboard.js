// ------------------ SECTION PORTFOLIO ------------------
let currentInterval = 'jour'; // Intervalle de temps sélectionné ('jour', 'semaine', etc.)

/**
 * Rafraîchit les données du portefeuille :
 * - Met à jour le graphique
 * - Met à jour les statistiques (ROI, PnL, nombre de transactions)
 * - Met à jour la valeur courante et le solde disponible
 */
function refreshPortfolioData() {
    fetch(`index.php?page=profilboard&action=refreshPortfolioData&pseudo=${pseudoleaderboard}&interval=${currentInterval}`)
    .then(res => res.json())
    .then(data => {
        updatePortfolioChart(data.chartData);

        // Sélection des éléments à mettre à jour
        const roiElement = document.getElementById('roi-total');
        const pnlElement = document.getElementById('pnl-total');

        // Mise à jour du texte
        roiElement.textContent = data.stats.roiTotal + ' %';
        pnlElement.textContent = data.stats.pnlTotal + ' USDT';

        // Réinitialisation des classes de couleur
        roiElement.classList.remove('positive', 'negative');
        pnlElement.classList.remove('positive', 'negative');

        // Ajout de la classe 'positive' ou 'negative' selon la valeur
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

        // Mise à jour des autres infos
        document.getElementById('tx-count').textContent = data.stats.txCount;
        document.getElementById('current-portfolio-value').textContent =
            "Current Value: " + data.currentValue + " USDT";
    })
    .catch(err => console.error(err));
}

// ------------------ CONFIGURATION DU GRAPHIQUE ------------------
let chart; // Référence au graphique Chart.js

/**
 * Formate une valeur monétaire pour l'axe vertical :
 * - Utilise 'M' pour les millions, 'k' pour les milliers, sinon arrondi simple
 */
function formatCurrency(value) {
    if (value >= 1_000_000) {
        return `$${(value / 1_000_000).toFixed(1)}M`;
    } else if (value >= 1_000) {
        return `$${(value / 1_000).toFixed(1)}k`;
    } else {
        return `$${Math.round(value)}`;
    }
}

/**
 * Met à jour (ou crée) le graphique avec les nouvelles données :
 * - labels : dates
 * - dataSolde : soldes arrondis
 * - crée un dégradé pour le remplissage sous la courbe
 */
function updatePortfolioChart(chartData) {
    const labels = chartData.map(d => d.date);
    const dataSolde = chartData.map(d => Math.round(d.solde));
    const ctx = document.getElementById('portfolioChart').getContext('2d');

    // Création d'un dégradé vertical pour l'arrière-plan du dataset
    let gradientStroke = ctx.createLinearGradient(0, 0, 0, 300);
    gradientStroke.addColorStop(0, "rgba(241, 196, 15, 0.8)");
    gradientStroke.addColorStop(1, "rgba(241, 196, 15, 0.2)");

    if (!chart) {
        // Initialisation du graphique si non existant
        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Portfolio Balance',
                    data: dataSolde,
                    borderColor: "rgba(241, 196, 15, 1)",
                    backgroundColor: gradientStroke,
                    fill: true,
                    tension: 0.4,
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
                        grid: { color: "rgba(255,255,255,0.1)" },
                        ticks: {
                            color: "#F8F9FA",
                            font: { family: "'Poppins', sans-serif", size: 12 }
                        }
                    },
                    y: {
                        display: true,
                        grid: { color: "rgba(255,255,255,0.1)" },
                        ticks: {
                            color: "#F8F9FA",
                            font: { family: "'Poppins', sans-serif", size: 12 },
                            callback: function(value) {
                                return formatCurrency(value);
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        backgroundColor: "rgba(0,0,0,0.7)",
                        titleFont: { family: "'Poppins', sans-serif", size: 14 },
                        bodyFont: { family: "'Poppins', sans-serif", size: 12 },
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const label = context.dataset.label || '';
                                const value = context.parsed.y;
                                return `${label}: ${formatCurrency(value)}`;
                            }
                        }
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
        // Mise à jour des données et rafraîchissement du graphique existant
        chart.data.labels = labels;
        chart.data.datasets[0].data = dataSolde;
        chart.update();
    }
}

// ------------------ GESTION DES BOUTONS D'INTERVALLE ------------------
document.querySelectorAll('.interval-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        // Désactivation visuelle de tous les boutons
        document.querySelectorAll('.interval-btn').forEach(b => b.classList.remove('active'));
        // Activation du bouton cliqué
        btn.classList.add('active');
        // Mise à jour de l'intervalle courant
        currentInterval = btn.getAttribute('data-interval');
        // Rafraîchissement des données
        refreshPortfolioData();
    });
});

// ------------------ INITIALISATION AU CHARGEMENT DE LA PAGE ------------------
document.addEventListener('DOMContentLoaded', () => {
    // Mettre en surbrillance le bouton 'jour' par défaut
    const defaultBtn = document.querySelector('.interval-btn[data-interval="jour"]');
    if (defaultBtn) {
        defaultBtn.classList.add('active');
    }
    // Lancement initial du rafraîchissement des données
    refreshPortfolioData();
    refreshProfilboardStats();
});

// ------------------ RAFAÎCHISSEMENT DES STATISTIQUES DU PROFILBOARD ------------------
function refreshProfilboardStats() {
    fetch(`index.php?page=profilboard&action=getStats&pseudo=${pseudoleaderboard}`)
        .then(res => res.json())
        .then(stats => {
            // Mise à jour de chaque élément avec les données reçues
            document.getElementById("stat-gagnantes").textContent = stats.gagnantes;
            document.getElementById("stat-perdantes").textContent = stats.perdantes;
            document.getElementById("stat-long").textContent = stats.total_long;
            document.getElementById("stat-long-win").textContent = stats.longs_gagnants;
            document.getElementById("stat-short").textContent = stats.total_short;
            document.getElementById("stat-short-win").textContent = stats.shorts_gagnants;
            document.getElementById("stat-pnl-moyen").textContent =
                parseFloat(stats.pnl_moyen).toFixed(2);
            document.getElementById("stat-temps-moyen").textContent =
                parseFloat(stats.temps_moyen_heures).toFixed(1);
            document.getElementById("stat-tx-mois").textContent =
                parseFloat(stats.tx_par_mois).toFixed(2);
        })
        .catch(err => console.error(err));
}
