<?php require_once RACINE . 'app/views/templates/header.php'; ?>

<div class="watchlist-container">
  <h2>Ma Watchlist</h2>

  <div class="table-responsive">
    <table id="watchlist-table" class="watchlist-table">
        <thead>
            <tr>
                <th>Crypto</th>
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
                        <td class="<?php echo $crypto['variation_24h'] >= 0 ? 'positive' : 'negative'; ?>">
          <?php echo htmlspecialchars($crypto['prix_actuel']); ?></td>
          <td class="<?php echo $crypto['variation_24h'] >= 0 ? 'positive' : 'negative'; ?>">
          <?php echo number_format($crypto['variation_24h'], 2, '.', '') . '%'; ?>
      </td>
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
  </div>

  <!-- Widget TradingView intégré -->
  <div id="tradingview-widget-container" class="tradingview-widget-container feature-trade"></div>
  
  <div class="market-link">
      <a href="index.php?page=market">Go to Market</a>
  </div>
</div>

<script src="<?php echo RACINE_URL; ?>public/js/watchlist.js"></script>

<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
