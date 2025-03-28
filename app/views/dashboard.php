<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once RACINE . 'app/views/templates/header.php';

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

<!-- Graphique TradingView intégré (widget gratuit) -->
<!-- Graphique TradingView intégré (widget gratuit) -->
<!-- Graphique TradingView intégré (widget gratuit) -->
<!-- Graphique TradingView intégré (widget gratuit) -->
<div class="tradingview-widget-container">
  <div id="tradingview_graph" style="height: 500px;"></div>
</div>
<!-- Graphique TradingView intégré (widget gratuit) -->
<!-- Graphique TradingView intégré (widget gratuit) -->
<!-- Graphique TradingView intégré (widget gratuit) -->
<!-- Graphique TradingView intégré (widget gratuit) -->


<!-- SECTION 2 : Trading (Long/Short) -->
<div id="trading-section" style="margin-bottom: 30px;">
    <h3>Passer un ordre</h3>
    <!-- Afficher le solde disponible (non alloué) -->
    <p id="available-balance">Solde disponible : Loading...</p>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'solde_insuffisant'): ?>
        <p style="color:red;">Solde insuffisant pour ouvrir cette position.</p>
    <?php endif; ?>

    <form action="index.php?page=dashboard&action=openPosition" method="POST">

        <!-- NOUVEAU : choix de la crypto -->
        <label for="crypto_code">Crypto :</label>
        <select name="crypto_code" id="crypto_code">
          <?php
// $cryptos a été défini dans showDashboard()
// On crée une option pour chaque code disponible
foreach ($cryptos as $code) {
    echo '<option value="' . htmlspecialchars($code) . '">' . htmlspecialchars($code) . '</option>';
}
?>
        </select>

        <br><br>

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
                <th>Taille</th>
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
<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://s3.tradingview.com/tv.js"></script>
<script src="<?php echo RACINE_URL; ?>public/js/dashboard.js"></script>

