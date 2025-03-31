
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
            
                // Couleur selon le sens
                let sensClass = pos.sens.toLowerCase() === 'long' ? 'positive' : 'negative';
            
                // Couleur PnL
                let pnl = parseFloat(pos.pnl);
                let pnlClass = pnl >= 0 ? 'positive' : 'negative';
            
                // Couleur ROI
                let roi = parseFloat(pos.roi);
                let roiClass = roi >= 0 ? 'positive' : 'negative';
            
                tr.innerHTML = `
                    <td>${pos.code}</td>
                    <td class="${sensClass}">${pos.sens}</td>
                    <td>${pos.prix_ouverture}</td>
                    <td>${pos.taille}</td>
                    <td>${pos.prix_actuel}</td>
                    <td>${pos.date_ouverture}</td>
                    <td class="${pnlClass}">${pnl.toFixed(2)}</td>
                    <td class="${roiClass}">${roi.toFixed(2)}%</td>
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

let tvWidget = null;

// Fonction d'initialisation du widget TradingView avec allow_symbol_change activé
function initTradingViewWidget(initialSymbol) {
    tvWidget = new TradingView.widget({
        container_id: "tradingview_graph",
        autosize: true,
        symbol: "BINANCE:" + initialSymbol,
        interval: "D",
        timezone: "Etc/UTC",
        theme: "dark",
        style: "1",
        locale: "fr",
        toolbar_bg: "#f1f3f6",
        enable_publishing: false,
        allow_symbol_change: false, // Autorise le changement de symbole
        hideideas: true
    });
}

document.addEventListener('DOMContentLoaded', () => {
    // Initialisation du widget sur BTCUSDT
    initTradingViewWidget('BTCUSDT');

    // Ajouter l'écouteur sur le select une fois que le DOM est prêt
    const selectCrypto = document.getElementById('crypto_code');
    if (selectCrypto) {
        selectCrypto.addEventListener('change', function () {
            const newSymbol = this.value;

            // Option 1 : Utiliser setSymbol si disponible
            if (tvWidget && tvWidget.activeChart && typeof tvWidget.activeChart().setSymbol === "function") {
                tvWidget.activeChart().setSymbol("BINANCE:" + newSymbol, function () {
                    console.log("Symbol changed to BINANCE:" + newSymbol);
                });
            } else {
                // Option 2 : Réinitialiser complètement le widget avec le nouveau symbole
                document.getElementById("tradingview_graph").innerHTML = "";
                initTradingViewWidget(newSymbol);
                console.log("Widget reinitialized with BINANCE:" + newSymbol);
            }
        });
    }
});


// ------------------ INITIALISATION ------------------
refreshPortfolioData();
refreshPositions();
setInterval(refreshPositions, 3000);
