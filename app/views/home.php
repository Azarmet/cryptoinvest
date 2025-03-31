<?php require_once RACINE . 'app/views/templates/header.php'; ?>

<div class="home-container">
    <h1>Bienvenue sur la page d'accueil</h1>
    <p>Ceci est la page d'accueil</p>
    <img class="fear-index" src="https://alternative.me/crypto/fear-and-greed-index.png" alt="Latest Crypto Fear & Greed Index" />

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

<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
<script src="<?php echo RACINE_URL; ?>public/js/home.js"></script>
