// ------------------ PORTFOLIO SECTION ------------------
let currentInterval = 'jour';

// Refresh portfolio (chart, stats, current value, and available balance)
function refreshPortfolioData() {
    fetch(`index.php?page=dashboard&action=refreshPortfolioData&interval=${currentInterval}`)
        .then(res => res.json())
        .then(data => {
            updatePortfolioChart(data.chartData);

            // Select elements
            const roiElement = document.getElementById('roi-total');
            const pnlElement = document.getElementById('pnl-total');

            // Update content
            roiElement.textContent = data.stats.roiTotal + ' %';
            pnlElement.textContent = data.stats.pnlTotal + ' USDT';

            // Reset classes
            roiElement.classList.remove('positive', 'negative');
            pnlElement.classList.remove('positive', 'negative');

            // Add classes based on values
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
            document.getElementById('current-portfolio-value').textContent = "Current Value: " + data.currentValue + " USDT";
            
        })
        .catch(err => console.error(err));
}

function refreshDashboardStats() {
    fetch("index.php?page=dashboard&action=getStats")
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


// Refresh positions
function refreshPositions() {
    fetch("index.php?page=dashboard&action=refreshPositions")
        .then(res => res.json())
        .then(positions => {
            const tbody = document.querySelector("#positions-table tbody");
            tbody.innerHTML = "";
            positions.forEach(pos => {
                let tr = document.createElement('tr');
            
                // Color based on side
                let sensClass = pos.sens.toLowerCase() === 'long' ? 'positive' : 'negative';
            
                // PnL color
                let pnl = parseFloat(pos.pnl);
                let pnlClass = pnl >= 0 ? 'positive' : 'negative';
            
                // ROI color
                let roi = parseFloat(pos.roi);
                let roiClass = roi >= 0 ? 'positive' : 'negative';
                document.getElementById('positions-number').textContent = "(" + positions.length + ")" ;

                tr.innerHTML = `
                    <td>${pos.code}</td>
                    <td class="${sensClass}">${pos.sens}</td>
                    <td>${pos.prix_ouverture}</td>
                    <td>${pos.taille}</td>
                    <td>${pos.prix_actuel}</td>
                    <td>${pos.date_ouverture}</td>
                    <td class="${pnlClass}">${pnl.toFixed(2)}</td>
                    <td class="${roiClass}">${roi.toFixed(2)}%</td>
                    <td><a href="index.php?page=dashboard&action=closePosition&id=${pos.id_transaction}" class="close-btn">Close</a></td>
                `;
                tbody.appendChild(tr);
            });
            
        })
        .catch(err => console.error(err));
}


// Chart management with Chart.js
let chart;

function formatCurrency(value) {
    if (value >= 1_000_000) {
        return `$${(value / 1_000_000).toFixed(1)}M`;
    } else if (value >= 1_000) {
        return `$${(value / 1_000).toFixed(1)}k`;
    } else {
        return `$${Math.round(value)}`;
    }
}

function updatePortfolioChart(chartData) {
    const labels = chartData.map(d => d.date);
    const dataSolde = chartData.map(d => Math.round(d.solde)); // ✅ Arrondi des données ici
    const ctx = document.getElementById('portfolioChart').getContext('2d');

    // Création du dégradé vertical
    let gradientStroke = ctx.createLinearGradient(0, 0, 0, 300);
    gradientStroke.addColorStop(0, "rgba(241, 196, 15, 0.8)");
    gradientStroke.addColorStop(1, "rgba(241, 196, 15, 0.2)");

    if (!chart) {
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
                            font: { family: "'Poppins', sans-serif", size: 12 },
                            callback: function(value) {
                                return formatCurrency(value); // ✅ Avec suffixe $
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
                                return `${label}: ${formatCurrency(value)}`; // ✅ Avec suffixe $
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
        chart.data.labels = labels;
        chart.data.datasets[0].data = dataSolde;
        chart.update();
    }
}




// ------------------ INTERVAL MANAGEMENT ------------------
document.querySelectorAll('.interval-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        // 1. Remove the "active" class from all buttons
        document.querySelectorAll('.interval-btn').forEach(b => b.classList.remove('active'));

        // 2. Add the "active" class to the clicked button
        btn.classList.add('active');

        // 3. Update the current interval
        currentInterval = btn.getAttribute('data-interval');

        // 4. Refresh the data
        refreshPortfolioData();
    });
});

// ------------------ INITIALIZATION ------------------
document.addEventListener('DOMContentLoaded', () => {
    const defaultBtn = document.querySelector('.interval-btn[data-interval="jour"]');
    if (defaultBtn) {
        defaultBtn.classList.add('active');
    }
    refreshPortfolioData();
});

refreshDashboardStats();
refreshPositions();
setInterval(refreshPositions, 3000);
