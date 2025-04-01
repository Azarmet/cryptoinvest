<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once RACINE . 'app/views/templates/header.php';

$userName = htmlspecialchars($_SESSION['user']['pseudo'] ?? 'Utilisateur');
?>

<div class="dashboard-container">
    <!-- Section d'accueil / Bienvenue -->
    <section class="welcome-section">
        <h2>Welcome <?php echo $userName; ?></h2>
        <h3 id="current-portfolio-value">Valeur actuelle : Loading...</h3>
    </section>

    <!-- SECTION 1 : Portefeuille -->
<section id="portfolio-section" class="portfolio-section">
    <h3>Mon Portefeuille</h3>
    <div class="portfolio-content">
        <div class="portfolio-left">
            <div class="interval-buttons">
                <button class="interval-btn" data-interval="jour">Jour</button>
                <button class="interval-btn" data-interval="semaine">Semaine</button>
                <button class="interval-btn" data-interval="mois">Mois</button>
                <button class="interval-btn" data-interval="annee">Année</button>
            </div>
            <div class="chart-container">
                <canvas id="portfolioChart"></canvas>
            </div>
        </div>
        <div class="portfolio-right">
            <div id="portfolio-stats" class="portfolio-stats">
                <div class="stat-item">
                    <p>ROI Total :</p>
                    <span id="roi-total">-</span>
                </div>
                <div class="stat-item">
                    <p>PnL Total :</p>
                    <span id="pnl-total">-</span>
                </div>
                <div class="stat-item">
                    <p>Nombre de transactions :</p>
                    <span id="tx-count">-</span>
                </div>
            </div>
        </div>
    </div>
</section>


    <!-- SECTION 2 : Trading -->
    <section id="trading-section" class="trading-section">
        <h3>Passer un ordre</h3>
        <p id="available-balance">Solde disponible : Loading...</p>
        <?php if (isset($_GET['error']) && $_GET['error'] === 'solde_insuffisant'): ?>
            <p class="error-msg">Solde insuffisant pour ouvrir cette position.</p>
        <?php endif; ?>
        <form action="index.php?page=dashboard&action=openPosition" method="POST" class="order-form">
            <div class="form-group">
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
            </div>
            <div class="form-group">
                <label for="montant">Montant (USDT) :</label>
                <input type="number" id="montant" name="montant" step="0.01" min="0">
            </div>
            <div class="form-group radio-group">
                <label>
                    <input type="radio" name="type" value="long" checked> Long
                </label>
                <label>
                    <input type="radio" name="type" value="short"> Short
                </label>
            </div>
            <button type="submit" class="submit-btn">Ouvrir une position</button>
        </form>
    </section>

    <!-- SECTION 3 : Positions en cours -->
    <section id="positions-section" class="positions-section">
        <h3>Mes Positions en cours</h3>
        <div class="table-responsive">
            <table id="positions-table">
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
    </section>
</div>

<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://s3.tradingview.com/tv.js"></script>
<script src="<?php echo RACINE_URL; ?>public/js/dashboard.js"></script>
