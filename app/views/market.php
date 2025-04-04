<?php require_once RACINE . 'app/views/templates/header.php'; ?>
<link rel="stylesheet" href="<?= RACINE_URL . 'public/css/market.css'?>">

<div class="market-container">
  <h2>Marché des Cryptomonnaies</h2>
  
  <!-- Onglets de catégories -->
  <div class="market-tabs">
    <button class="tab-button" data-category="all">Toutes</button>
    <button class="tab-button active" data-category="top">Top 10</button>
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
  <div class="search-container">
    <input type="text" id="searchInput" class="search-input" placeholder="Rechercher une crypto...">
    <button type="button" id="searchButton" class="search-button">Rechercher</button>
</div>

  <!-- Tableau du marché -->
  <div class="table-responsive-market">
    <table id="market-table" class="market-table">
      <thead>
        <tr>
          <th>Crypto</th>
          <th>Actual Price</th>
          <th>Variation 24H</th>
          <?php if (isset($_SESSION['user'])): ?>
            <th>Watchlist</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
  <?php foreach ($cryptos as $crypto): ?>
    <tr>
      <td><?php echo htmlspecialchars($crypto['code']); ?></td>
      <td class="<?php echo $crypto['variation_24h'] >= 0 ? 'positive' : 'negative'; ?>">
          <?php echo htmlspecialchars($crypto['prix_actuel']); ?>
      </td>
      <td class="<?php echo $crypto['variation_24h'] >= 0 ? 'positive' : 'negative'; ?>">
          <?php echo number_format($crypto['variation_24h'], 2, '.', '') . '%'; ?>
      </td>
      <?php if (isset($_SESSION['user'])): ?>
        <td>
          <?php if (!empty($crypto['in_watchlist'])): ?>
              <button class="watchlist-toggle" data-action="remove" data-id="<?php echo $crypto['id_crypto_market']; ?>">✅</button>
          <?php else: ?>
              <button class="watchlist-toggle" data-action="add" data-id="<?php echo $crypto['id_crypto_market']; ?>">❌</button>
          <?php endif; ?>
        </td>
      <?php endif; ?>
    </tr>
  <?php endforeach; ?>
</tbody>

    </table>
  </div>
    <div class="tradingwidget-order">
        <!-- Widget TradingView intégré -->
        <div id="tradingview-widget-container" class="tradingview-widget-container feature-trade"></div>
        <?php if (isset($_SESSION['user'])): ?>
        <div class="trading-order">
            <?php require_once RACINE . 'app/views/templates/tradingOrder.php'; ?>
        </div>
        <?php else:?>
            <div class="trading-order trading-order2" style="background-image: url('<?= RACINE_URL ?>public/image/order-img.jpg');">
            <p>Vous devez être connecté pour trader</p>
        </div> 
        <?php endif; ?>
    </div>
    <?php if (isset($_SESSION['user'])): ?>
    <!-- SECTION 3 : Positions en cours -->
    <?php require_once RACINE . 'app/views/templates/positions.php'; ?>
    <?php endif; ?>



  <?php if (isset($_SESSION['user'])): ?>
    <div class="watchlist-link">
      <a href="index.php?page=watchlist">Go to Watchlist</a>
    </div>
  <?php endif; ?>
</div>

<script>
    var isLoggedIn = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;
</script>
<script src="<?php echo RACINE_URL; ?>public/js/market.js"></script>

<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
