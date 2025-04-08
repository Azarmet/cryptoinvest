<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once RACINE . 'app/views/templates/header.php';
?>
<div class="profilboard-container">
<div class="user-profile">
    <img id="profil-photo" src="<?= $profiluser['image_profil'] ?>" alt="Photo de profil" class="profile-image">
    <div class="profile-details">
        <h2 id="profil-pseudo">Profil de <?= $profiluser['pseudo'] ?></h2>
        <p id="profil-bio"><?= $profiluser['bio'] ?></p>
    </div>
</div>
<!-- <h3 id="current-portfolio-value">Valeur actuelle : Loading...</h3> -->
    <?php require_once RACINE . 'app/views/templates/portfolio.php'; ?>

</div>

<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
<script>
    var pseudoleaderboard = "<?= $profiluser['pseudo'] ?>";
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?php echo RACINE_URL; ?>public/js/profilboard.js"></script>
