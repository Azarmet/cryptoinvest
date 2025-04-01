<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once RACINE . 'app/views/templates/header.php';
?>
<h1>Profil de <?= $profiluser['pseudo'] ?> </h1>
<!-- SECTION 1 : Graphique du portefeuille + indicateurs -->
<section id="portfolio-section" class="portfolio-section">
    <h3>Portefeuille</h3>
    <div class="portfolio-content">
        <div class="portfolio-left">
            
            <!-- Choix de l'intervalle -->
            <div class="interval-buttons">
                <button class="interval-btn" data-interval="jour">Jour</button>
                <button class="interval-btn" data-interval="semaine">Semaine</button>
                <button class="interval-btn" data-interval="mois">Mois</button>
                <button class="interval-btn" data-interval="annee">Année</button>
            </div>
            <!-- Graphique (Canvas pour Chart.js) -->
            <div class="chart-container">
                <canvas id="portfolioChart" style="border:1px solid #ccc;"></canvas>
            </div>
        </div>
        <div class="portfolio-right">
            <p><span id="current-portfolio-value">-</span></p>
            <!-- Indicateurs clés -->
            <div id="portfolio-stats" class="portfolio-stats">
                
                <p>ROI Total : <span id="roi-total">-</span></p>
                <p>PnL Total : <span id="pnl-total">-</span></p>
                <p>Nombre de transactions : <span id="tx-count">-</span></p>
            </div>
        </div>
    </div>
</section>
<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
<script>
    var pseudoleaderboard = "<?= $profiluser['pseudo'] ?>";
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?php echo RACINE_URL; ?>public/js/profilboard.js"></script>
