<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once RACINE . "app/views/templates/header.php";

$userName = htmlspecialchars($_SESSION['user']['pseudo'] ?? 'Utilisateur');
?>

<h2>Welcome <?php echo $userName; ?></h2>
<!-- Affichage de la valeur actuelle du portefeuille -->
<h3 id="current-portfolio-value">Valeur actuelle : Loading...</h3>

<!-- SECTION 1 : Graphique du portefeuille + indicateurs -->
<div id="portfolio-section" style="margin-bottom: 30px;">
    <h3>Mon Portefeuille</h3>
    <!-- Choix de l'intervalle -->
    <div>
        <button class="interval-btn" data-interval="jour">Jour</button>
        <button class="interval-btn" data-interval="semaine">Semaine</button>
        <button class="interval-btn" data-interval="mois">Mois</button>
        <button class="interval-btn" data-interval="annee">Année</button>
    </div>

    <!-- Graphique (Canvas pour Chart.js) -->
    <canvas id="portfolioChart" width="600" height="300" style="border:1px solid #ccc;"></canvas>

    <!-- Indicateurs clés -->
    <div id="portfolio-stats" style="margin-top: 15px;">
        <p>ROI Total : <span id="roi-total">-</span></p>
        <p>PnL Total : <span id="pnl-total">-</span></p>
        <p>Nombre de transactions : <span id="tx-count">-</span></p>
    </div>
</div>

<!-- SECTION 2 : Trading (Long/Short) -->
<div id="trading-section" style="margin-bottom: 30px;">
    <h3>Passer un ordre (BTCUSDT)</h3>
    <?php if(isset($_GET['error']) && $_GET['error'] === 'solde_insuffisant'): ?>
        <p style="color:red;">Solde insuffisant pour ouvrir cette position.</p>
    <?php endif; ?>
    <form action="index.php?page=dashboard&action=openPosition" method="POST">
        <label for="montant">Montant (USDT) :</label>
        <input type="number" id="montant" name="montant" step="0.01" min="0">
        <div>
            <label>
                <input type="radio" name="type" value="long" checked> Long
            </label>
            <label>
                <input type="radio" name="type" value="short"> Short
            </label>
        </div>
        <button type="submit">Ouvrir une position</button>
    </form>
</div>

<!-- SECTION 3 : Positions en cours -->
<div id="positions-section">
    <h3>Mes Positions en cours</h3>
    <table id="positions-table" border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Crypto</th>
                <th>Type</th>
                <th>Prix d'ouverture</th>
                <th>Prix actuel</th>
                <th>Date ouverture</th>
                <th>PNL (USDT)</th>
                <th>ROI (%)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Les positions seront rafraîchies en AJAX -->
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ------------------ PORTFOLIO SECTION ------------------
let currentInterval = 'jour';

// Rafraîchissement du portefeuille (graphique, stats et valeur actuelle)
function refreshPortfolioData() {
    fetch(`index.php?page=dashboard&action=refreshPortfolioData&interval=${currentInterval}`)
        .then(res => res.json())
        .then(data => {
            updatePortfolioChart(data.chartData);
            document.getElementById('roi-total').textContent = data.stats.roiTotal + ' %';
            document.getElementById('pnl-total').textContent = data.stats.pnlTotal + ' USDT';
            document.getElementById('tx-count').textContent = data.stats.txCount;
            // Affichage du capital_actuel
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

// ------------------ POSITIONS SECTION ------------------
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

// ------------------ INITIALISATION ------------------
refreshPortfolioData();
refreshPositions();
setInterval(refreshPositions, 1000);
setInterval(refreshPortfolioData, 1000);
</script>

<?php require_once RACINE . "app/views/templates/footer.php"; ?>
