
// ------------------ PORTFOLIO SECTION ------------------
let currentInterval = 'jour';

// Rafraîchissement du portefeuille (graphique, stats, valeur actuelle et solde disponible)
function refreshPortfolioData() {
    fetch(`index.php?page=dashboard&action=refreshPortfolioData&interval=${currentInterval}`)
        .then(res => res.json())
        .then(data => {
            updatePortfolioChart(data.chartData);
            document.getElementById('roi-total').textContent = data.stats.roiTotal + ' %';
            document.getElementById('pnl-total').textContent = data.stats.pnlTotal + ' USDT';
            document.getElementById('tx-count').textContent = data.stats.txCount;
            document.getElementById('current-portfolio-value').textContent = "Valeur actuelle : " + data.currentValue + " USDT";
            document.getElementById('available-balance').textContent = "Solde disponible : " + data.availableBalance + " USDT";
        })
        .catch(err => console.error(err));
}

// Rafraîchissement des positions
function refreshPositions() {
    fetch("index.php?page=dashboard&action=refreshPositions")
        .then(res => res.json())
        .then(positions => {
            const tbody = document.querySelector("#positions-table tbody");
            tbody.innerHTML = "";
            positions.forEach(pos => {
                let tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${pos.code}</td>
                    <td>${pos.sens}</td>
                    <td>${pos.prix_ouverture}</td>
                    <td>${pos.taille}</td>
                    <td>${pos.prix_actuel}</td>
                    <td>${pos.date_ouverture}</td>
                    <td>${pos.pnl}</td>
                    <td>${pos.roi}</td>
                    <td><a href="index.php?page=dashboard&action=closePosition&id=${pos.id_transaction}">Clôturer</a></td>
                `;
                tbody.appendChild(tr);
            });
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


// ------------------ INITIALISATION ------------------
refreshPortfolioData();
refreshPositions();
setInterval(refreshPositions, 3000);
