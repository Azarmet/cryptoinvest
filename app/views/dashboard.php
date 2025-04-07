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
    <?php require_once RACINE . 'app/views/templates/portfolio.php'; ?>





    <!-- SECTION 3 : Positions en cours -->
    <?php require_once RACINE . 'app/views/templates/positions.php'; ?>

    <!-- SECTION 3 : Historique des transactions -->
    <?php require_once RACINE . 'app/views/templates/history.php'; ?>



</div>

<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://s3.tradingview.com/tv.js"></script>
<script src="<?php echo RACINE_URL; ?>public/js/dashboard.js"></script>
