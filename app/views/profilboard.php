<?php
// Démarrage de la session si nécessaire
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Inclusion de l'en-tête du template (logo, navigation, CSS, etc.)
require_once RACINE . 'app/views/templates/header.php';
?>
<div class="profilboard-container">

    <!-- Section profil utilisateur affiché en mode public -->
    <div class="user-profile">
        <!-- Photo de profil de l'utilisateur consulté -->
        <img id="profil-photo" src="<?= $profiluser['image_profil'] ?>" alt="Profile Picture" class="profile-image">
        <div class="profile-details">
            <!-- Pseudo de l'utilisateur consulté -->
            <h2 id="profil-pseudo">Profile of <?= $profiluser['pseudo'] ?></h2>
            <!-- Biographie (plain text, sans balises HTML) -->
            <p id="profil-bio"><?= htmlspecialchars($profiluser['bio']) ?></p>
            <!-- Liens vers les réseaux sociaux, affichés seulement s'ils existent -->
            <div class="social-links">
                <?php if (!empty($profiluser['instagram'])): ?>
                    <?php 
                        // Construction de l'URL Instagram sécurisée et extraction du handle
                        $instaUrl     = htmlspecialchars($profiluser['instagram']);
                        $instaHandle  = '@' . basename(parse_url($instaUrl, PHP_URL_PATH));
                    ?>
                    <a href="<?= $instaUrl ?>" target="_blank" class="social-link instagram">
                        <i class="fab fa-instagram"></i> <?= $instaHandle ?>
                    </a>
                <?php endif; ?>

                <?php if (!empty($profiluser['x'])): ?>
                    <?php 
                        // Construction de l'URL X (anciennement Twitter) et extraction du handle
                        $xUrl    = htmlspecialchars($profiluser['x']);
                        $xHandle = '@' . basename(parse_url($xUrl, PHP_URL_PATH));
                    ?>
                    <a href="<?= $xUrl ?>" target="_blank" class="social-link x">
                        <i class="fab fa-x-twitter"></i> <?= $xHandle ?>
                    </a>
                <?php endif; ?>

                <?php if (!empty($profiluser['telegram'])): ?>
                    <?php 
                        // Préfixe @ pour Telegram et génération du lien complet vers t.me
                        $tgUsername = '@' . ltrim($profiluser['telegram'], '@');
                        $tgLink     = 'https://t.me/' . ltrim($profiluser['telegram'], '@');
                    ?>
                    <a href="<?= $tgLink ?>" target="_blank" class="social-link telegram">
                        <i class="fab fa-telegram"></i> <?= $tgUsername ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Inclusion de la section portefeuille (graphique et statistiques) -->
    <?php require_once RACINE . 'app/views/templates/portfolio.php'; ?>

</div>

<?php
// Inclusion du pied de page du template (footer, scripts communs)
require_once RACINE . 'app/views/templates/footer.php';
?>
<script>
    // Passage du pseudo courant au script JavaScript pour requêtes AJAX
    var pseudoleaderboard = "<?= $profiluser['pseudo'] ?>";
</script>
<!-- Librairie Chart.js pour affichage des graphiques -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Script spécifique à la page Profil Board (rafraîchissement données via AJAX) -->
<script src="<?php echo RACINE_URL; ?>public/js/profilboard.js"></script>
