<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once RACINE . 'app/views/templates/header.php';
?>
<div class="profilboard-container">
<h1 class="profilboard-h1">Profil de <?= $profiluser['pseudo'] ?> </h1>
<h3 id="current-portfolio-value">Valeur actuelle : Loading...</h3>
    <?php require_once RACINE . 'app/views/templates/portfolio.php'; ?>

</div>

<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
<script>
    var pseudoleaderboard = "<?= $profiluser['pseudo'] ?>";
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?php echo RACINE_URL; ?>public/js/profilboard.js"></script>
