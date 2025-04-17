<?php require_once RACINE . 'app/views/templates/header.php'; ?>

<div class="home-container">
    <!-- Hero Section with a tagline, background image and button -->
    <section class="hero">
        <div class="hero-content">
            <h1>Your <span class="orange">crypto future</span> begins<span class="orange"> here</span></h1>
            <p>Join us to explore the exciting world of cryptocurrency</p>
            <?php if (isset($_SESSION['user'])): ?>
                <a href="index.php?page=dashboard" class="btn-get-started">Get Started</a>
            <?php else: ?>
                <a href="index.php?page=login" class="btn-get-started">Get Started</a>
            <?php endif; ?>
        </div>
    </section>
    <section class="home-features">
        <!-- Section 1: Trade Virtual Funds -->
        <div class="feature trade">
            <div class="feature-bg" style="background-image: url('<?= RACINE_URL ?>public/image/trade-bg.jpg');"></div>
            <div class="feature-overlay"></div>
            <div class="feature-content">
                <h2><span class="orange">Trade</span> Virtual Funds</h2>
                <p>Simulate real-time transactions with virtual funds to test your strategies risk-free.</p>
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="index.php?page=dashboard" class="btn-get-started">Let's go</a>
                <?php else: ?>
                    <a href="index.php?page=login" class="btn-get-started">Let's go</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Section 2: Learn about Crypto and Trading -->
        <?php require_once RACINE . 'app/views/templates/learnSection.php'; ?>

        <!-- Section combining market data and the Fear & Greed image -->
        <section class="market-fear">
            <div class="market-data">
                <?php require_once RACINE . 'app/views/templates/markettop10.php'; ?>
            </div>
            <section class="fear-leader">
                <?php require_once RACINE . 'app/views/templates/fearGreed.php'; ?>
                
                <div class="leader">
                    <?php require_once RACINE . 'app/views/templates/leaderboard.php'; ?>
                </div>
            </section>
        </section>
    </section>
</div>

<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
<script src="<?php echo RACINE_URL; ?>public/js/home.js"></script>
<script src="<?php echo RACINE_URL; ?>public/js/fearGreed.js"></script>


