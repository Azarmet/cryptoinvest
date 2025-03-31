<?php require_once RACINE . 'app/views/templates/header.php'; ?>

<div class="home-container">
    <!-- Section Hero avec phrase d'accroche, image de fond et bouton -->
    <section class="hero">
        <div class="hero-content">
            <h1>Votre <span class="orange">avenir</span>  crypto commence<span class="orange"> ici</span> </h1>
            <p>Rejoignez-nous pour explorer le monde passionnant de la crypto-monnaie</p>
            <a href="getstarted.php" class="btn-get-started">Get Started</a>
        </div>
    </section>

    <!-- Section regroupant les données de marché et l'image Fear & Greed -->
    <section class="market-fear">
        <div class="market-data">
            <section class="market-top10">
                <h2>Top 10 Cryptos</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Symbole</th>
                            <th>Prix (USD)</th>
                            <th>Variation (24h)</th>
                        </tr>
                    </thead>
                    <tbody id="top10-market">
                        <!-- Rempli dynamiquement en JS -->
                    </tbody>
                </table>
            </section>
        </div>
        <div class="fear-image">
            <img class="fear-index" src="https://alternative.me/crypto/fear-and-greed-index.png" alt="Latest Crypto Fear & Greed Index" />
        </div>
    </section>
</div>

<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
<script src="<?php echo RACINE_URL; ?>public/js/home.js"></script>
