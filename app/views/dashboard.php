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
    <?php require_once RACINE . 'app/views/templates/tradingOrder.php'; ?>


    <!-- SECTION 3 : Positions en cours -->
    <?php require_once RACINE . 'app/views/templates/positions.php'; ?>

</div>

<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://s3.tradingview.com/tv.js"></script>
<script src="<?php echo RACINE_URL; ?>public/js/dashboard.js"></script>
