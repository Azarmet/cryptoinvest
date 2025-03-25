<?php require_once RACINE . "app/views/templates/header.php"; ?>

<h2>Ma Watchlist</h2>

<table id="watchlist-table" border="1" cellpadding="5">
    <thead>
        <tr>
            <th></th>
            <th>Actual Price</th>
            <th>Variation 24H</th>
            <th>Last Update</th>
            <th>Watchlist</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($cryptos)): ?>
            <?php foreach ($cryptos as $crypto): ?>
                <tr>
                    <td><?php echo htmlspecialchars($crypto['code']); ?></td>
                    <td><?php echo htmlspecialchars($crypto['prix_actuel']); ?></td>
                    <td><?php echo number_format($crypto['variation_24h'], 2, '.', '') ."%"; ?></td>
                    <td><?php echo htmlspecialchars($crypto['date_maj']); ?></td>
                    <td>
                        <a href="index.php?page=watchlist&action=remove&id=<?php echo $crypto['id_crypto_market']; ?>">Remove</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">Votre watchlist est vide.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<!-- Graphique TradingView intégré (widget gratuit) -->
<!-- Graphique TradingView intégré (widget gratuit) -->
<!-- Graphique TradingView intégré (widget gratuit) -->
<!-- Graphique TradingView intégré (widget gratuit) -->
<div id="tradingview-widget-container-watch">
</div>
<!-- Graphique TradingView intégré (widget gratuit) -->
<!-- Graphique TradingView intégré (widget gratuit) -->
<!-- Graphique TradingView intégré (widget gratuit) -->
<!-- Graphique TradingView intégré (widget gratuit) -->
<a href="index.php?page=market">Go to Market</a>

<script src="<?php echo RACINE_URL; ?>public/js/watchlist.js"></script>

<?php require_once RACINE . "app/views/templates/footer.php"; ?>
