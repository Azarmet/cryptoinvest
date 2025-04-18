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
        <h2>Welcome <?=$userName; ?></h2>
    </section>

    <!-- SECTION 1 : Portefeuille -->
    <?php require_once RACINE . 'app/views/templates/portfolio.php'; ?>





    <!-- SECTION 3 : Positions en cours -->
    <?php require_once RACINE . 'app/views/templates/positions.php'; ?>

    <!-- SECTION 3 : Historique des transactions -->
    <?php require_once RACINE . 'app/views/templates/history.php'; ?>

    <!-- Bouton How to trade -->
<button id="howToTradeBtn" class="how-to-trade-btn">
  How to trade
</button>

<!-- Modal pour la vidéo -->
<div id="howToTradeModal" class="modal">
  <div class="modal-content">
    <span class="close-btn">&times;</span>
    <video controls width="100%">
      <source src="<?= RACINE_URL ?>public/video/tuto-trade.mp4" type="video/mp4">
      Votre navigateur ne supporte pas la vidéo.
    </video>
  </div>
</div>


</div>

<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://s3.tradingview.com/tv.js"></script>
<script src="<?php echo RACINE_URL; ?>public/js/dashboard.js"></script>
