<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once RACINE . 'app/views/templates/header.php';
?>
<div class="profilboard-container">
<div class="user-profile">
    <img id="profil-photo" src="<?= $profiluser['image_profil'] ?>" alt="Profile Picture" class="profile-image">
    <div class="profile-details">
        <h2 id="profil-pseudo">Profile of <?= $profiluser['pseudo'] ?></h2>
        <p id="profil-bio"><?= $profiluser['bio'] ?></p>
        <div class="social-links">
    <?php if (!empty($profiluser['instagram'])): ?>
        <?php 
            $instaUrl = htmlspecialchars($profiluser['instagram']);
            $instaHandle = '@' . basename(parse_url($instaUrl, PHP_URL_PATH));
        ?>
        <a href="<?= $instaUrl ?>" target="_blank" class="social-link instagram">
            <i class="fab fa-instagram"></i> <?= $instaHandle ?>
        </a>
    <?php endif; ?>

    <?php if (!empty($profiluser['x'])): ?>
        <?php 
            $xUrl = htmlspecialchars($profiluser['x']);
            $xHandle = '@' . basename(parse_url($xUrl, PHP_URL_PATH));
        ?>
        <a href="<?= $xUrl ?>" target="_blank" class="social-link x">
            <i class="fab fa-x-twitter"></i> <?= $xHandle ?>
        </a>
    <?php endif; ?>

    <?php if (!empty($profiluser['telegram'])): ?>
        <?php 
            $tgUsername = '@' . ltrim($profiluser['telegram'], '@');
            $tgLink = 'https://t.me/' . ltrim($profiluser['telegram'], '@');
        ?>
        <a href="<?= $tgLink ?>" target="_blank" class="social-link telegram">
            <i class="fab fa-telegram"></i> <?= $tgUsername ?>
        </a>
    <?php endif; ?>
</div>


    </div>
</div>
<!-- <h3 id="current-portfolio-value">Current Value: Loading...</h3> -->
    <?php require_once RACINE . 'app/views/templates/portfolio.php'; ?>

</div>

<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
<script>
    var pseudoleaderboard = "<?= $profiluser['pseudo'] ?>";
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?php echo RACINE_URL; ?>public/js/profilboard.js"></script>
