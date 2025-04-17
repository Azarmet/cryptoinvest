<?php require_once RACINE . 'app/views/templates/header.php'; ?>

<div class="watchlist-container">
    <!-- Titre de la section Watchlist -->
    <h2>My Watchlist</h2>

    <!-- Tableau réactif affichant la liste des cryptos mises en watchlist -->
    <div class="table-responsive">
        <table id="watchlist-table" class="watchlist-table">
            <thead>
                <tr>
                    <th>Crypto</th>
                    <th>Current Price</th>
                    <th>24H Change</th>
                    <th>Last Update</th>
                    <th>Watchlist</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($cryptos)): ?>
                    <!-- Boucle sur chaque crypto pour générer une ligne du tableau -->
                    <?php foreach ($cryptos as $crypto): ?>
                        <tr>
                            <!-- Nom du symbole -->
                            <td><?= htmlspecialchars($crypto['code']) ?></td>
                            <!-- Prix actuel, mise en forme conditionnelle selon la variation -->
                            <td class="<?= $crypto['variation_24h'] >= 0 ? 'positive' : 'negative'; ?>">
                                <?= htmlspecialchars($crypto['prix_actuel']) ?>
                            </td>
                            <!-- Variation 24h, formatée avec 2 décimales et un signe % -->
                            <td class="<?= $crypto['variation_24h'] >= 0 ? 'positive' : 'negative'; ?>">
                                <?= number_format($crypto['variation_24h'], 2, '.', '') . '%' ?>
                            </td>
                            <!-- Date de la dernière mise à jour -->
                            <td><?= htmlspecialchars($crypto['date_maj']) ?></td>
                            <!-- Lien pour retirer la crypto de la watchlist -->
                            <td>
                                <a href="index.php?page=watchlist&action=remove&id=<?= $crypto['id_crypto_market'] ?>">
                                    Remove
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Message affiché si la watchlist est vide -->
                    <tr>
                        <td colspan="5">Your watchlist is empty.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Widget TradingView pour afficher un graphique de marché -->
    <div id="tradingview-widget-container" class="tradingview-widget-container feature-trade"></div>
    
    <!-- Lien vers la page principale du marché -->
    <div class="market-link">
        <a href="index.php?page=market">Go to Market</a>
    </div>
</div>

<!-- Chargement du script de rafraîchissement et d'interaction AJAX de la watchlist -->
<script src="<?php echo RACINE_URL; ?>public/js/watchlist.js"></script>

<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
