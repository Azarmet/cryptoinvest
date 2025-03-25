<?php require_once RACINE . "app/views/templates/header.php"; ?>

<h2>Marché des Cryptomonnaies</h2>
<div class="market-tabs">
  <button class="tab-button active" data-category="all">Toutes</button>
  <button class="tab-button" data-category="top">Top 10</button>
  <button class="tab-button" data-category="ino">Innovation</button>
  <button class="tab-button" data-category="defi">DeFi</button>
  <button class="tab-button" data-category="nft">NFT / Metaverse</button>
  <button class="tab-button" data-category="meme">Meme</button>
  <button class="tab-button" data-category="AI">AI</button>
  <button class="tab-button" data-category="layer1">Layer 1</button>
  <button class="tab-button" data-category="layer2">Layer 2</button>
  <button class="tab-button" data-category="web3">Web3</button>
  <button class="tab-button" data-category="new">Nouveautés</button>
</div>


<table id="market-table" border="1" cellpadding="5">
    <thead>
        <tr>
            <th></th>
            <th>Actual Price</th>
            <th>Variation 24H</th>
            <th>Last Update</th>
            <?php if(isset($_SESSION['user'])): ?>
                <th>Watchlist</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cryptos as $crypto): ?>
        <tr>
            <td><?php echo htmlspecialchars($crypto['code']); ?></td>
            <td><?php echo htmlspecialchars($crypto['prix_actuel']); ?></td>
            <td><?php echo number_format($crypto['variation_24h'], 2, '.', '') ."%"; ?></td>
            <td><?php echo htmlspecialchars($crypto['date_maj']); ?></td>
            <?php if(isset($_SESSION['user'])): ?>
                <td>
                    <a href="index.php?page=watchlist&action=add&id=<?php echo $crypto['id_crypto_market']; ?>">
                        Add
                    </a>
                </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<!-- Graphique TradingView intégré (widget gratuit) -->
<!-- Graphique TradingView intégré (widget gratuit) -->
<!-- Graphique TradingView intégré (widget gratuit) -->
<!-- Graphique TradingView intégré (widget gratuit) -->
<div id="tradingview-widget-container">
</div>
<!-- Graphique TradingView intégré (widget gratuit) -->
<!-- Graphique TradingView intégré (widget gratuit) -->
<!-- Graphique TradingView intégré (widget gratuit) -->
<!-- Graphique TradingView intégré (widget gratuit) -->
<?php if(isset($_SESSION['user'])): ?>
    <a href="index.php?page=watchlist">Go to Watchlist</a>
<?php endif; ?>
<script>
    var isLoggedIn = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;
</script>
<script src="<?php echo RACINE_URL; ?>public/js/market.js"></script>


<?php require_once RACINE . "app/views/templates/footer.php"; ?>
