<?php require_once RACINE . 'app/views/templates/header.php'; ?>

<!-- Conteneur principal de la page d'accueil -->
<div class="home-container">

    <!-- SECTION HERO : Bannière d'accueil avec slogan et bouton -->
    <section class="hero">
        <div class="hero-content">
            <h1>Your <span class="orange">crypto future</span> begins<span class="orange"> here</span></h1>
            <p>Simulate. Learn. Trade with Confidence.</p>
            <!-- Bouton conditionnel : dirige vers le dashboard si connecté, sinon vers le formulaire de connexion -->
            <?php if (isset($_SESSION['user'])): ?>
                <a href="index.php?page=dashboard" class="btn-get-started">Get Started</a>
            <?php else: ?>
                <a href="index.php?page=login" class="btn-get-started">Get Started</a>
            <?php endif; ?>
        </div>
    </section>

    <!-- SECTION FEATURES : Présentation des fonctionnalités principales -->
    <section class="home-features">

        <!-- FEATURE 1 : Simulation de trading avec fonds virtuels -->
        <div class="feature trade">
            <div class="feature-bg" style="background-image: url('<?= RACINE_URL ?>public/image/trade-bg.webp');"></div>
            <div class="feature-overlay"></div>
            <div class="feature-content">
                <h2><span class="orange">Trade</span> Virtual Funds</h2>
                <p>Simulate real-time transactions with virtual funds to test your strategies risk-free.</p>
                <!-- Bouton vers le dashboard ou la page de connexion selon l'état de session -->
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="index.php?page=dashboard" class="btn-get-started">Let's go</a>
                <?php else: ?>
                    <a href="index.php?page=login" class="btn-get-started">Let's go</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- FEATURE 2 : Section d'apprentissage incluse depuis templates/learnSection.php -->
        <?php require_once RACINE . 'app/views/templates/learnSection.php'; ?>

        <!-- SECTION MARCHÉ & FEAR & GREED : Combine le Top10 marché et l'indice Fear & Greed -->
        <section class="market-fear">
            <!-- Sous-section : Affichage du Top10 du marché -->
            <div class="market-data">
                <?php require_once RACINE . 'app/views/templates/markettop10.php'; ?>
            </div>
            <section class="fear-leader">
                <!-- Sous-section : Indice Crypto Fear & Greed -->
                <?php require_once RACINE . 'app/views/templates/fearGreed.php'; ?>
                
                <!-- Sous-section : Leaderboard -->
                <div class="leader">
                    <?php require_once RACINE . 'app/views/templates/leaderboard.php'; ?>
                </div>
            </section>
        </section>

    </section>
</div>

<!-- Inclusion du pied de page commun -->
<?php require_once RACINE . 'app/views/templates/footer.php'; ?>

<!-- Scripts JavaScript spécifiques à la page d'accueil -->
<script src="<?php echo RACINE_URL; ?>public/js/home.js"></script>
<script src="<?php echo RACINE_URL; ?>public/js/fearGreed.js"></script>
