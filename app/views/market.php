<?php require_once RACINE . 'app/views/templates/header.php'; ?>

<!-- Conteneur principal de la page Market -->
<div class="market-container">  
  <h1 class="market_title">Mar<span class="orange">ket</span></h1>

  <!-- Onglets de sélection de catégorie -->
  <div class="market-tabs">
    <!-- Chaque bouton change la catégorie affichée -->
    <button class="tab-button" data-category="all">All</button>
    <button class="tab-button active" data-category="top">Top 10</button>
    <button class="tab-button" data-category="ino">Innovation</button>
    <button class="tab-button" data-category="defi">DeFi</button>
    <button class="tab-button" data-category="nft">NFT / Metaverse</button>
    <button class="tab-button" data-category="meme">Meme</button>
    <button class="tab-button" data-category="AI">AI</button>
    <button class="tab-button" data-category="layer1">Layer 1</button>
    <button class="tab-button" data-category="layer2">Layer 2</button>
    <button class="tab-button" data-category="web3">Web3</button>
    <button class="tab-button" data-category="new">New</button>
  </div>

  <!-- Champ de recherche pour filtrer le tableau -->
  <div class="search-container">
    <input type="text" id="searchInput" class="search-input" placeholder="Search for a crypto...">
    <button type="button" id="searchButton" class="search-button">Search</button>
  </div>

  <!-- Tableau listant les cryptomonnaies selon la catégorie sélectionnée -->
  <div class="table-responsive-market">
    <table id="market-table" class="market-table">
      <thead>
        <tr>
          <th>Crypto</th>
          <th>Price</th>
          <th>24H Change</th>
          <!-- Colonne Watchlist affichée uniquement si l'utilisateur est connecté -->
          <?php if (isset($_SESSION['user'])): ?>
            <th>Watchlist</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cryptos as $crypto): ?>
          <tr>
            <!-- Symbole de la cryptomonnaie -->
            <td><?php echo htmlspecialchars($crypto['code']); ?></td>

            <!-- Prix actuel, couleur selon variation positive ou négative -->
            <td class="<?php echo $crypto['variation_24h'] >= 0 ? 'positive' : 'negative'; ?>">
              <?php echo htmlspecialchars($crypto['prix_actuel']); ?>
            </td>

            <!-- Variation sur 24h, formatée et colorée -->
            <td class="<?php echo $crypto['variation_24h'] >= 0 ? 'positive' : 'negative'; ?>">
              <?php echo number_format($crypto['variation_24h'], 2, '.', '') . '%'; ?>
            </td>

            <?php if (isset($_SESSION['user'])): ?>
              <!-- Bouton d'ajout/suppression de la watchlist -->
              <td>
                <?php if (!empty($crypto['in_watchlist'])): ?>
                  <!-- Si déjà en watchlist, afficher bouton "supprimer" -->
                  <button class="watchlist-toggle" data-action="remove" data-id="<?php echo $crypto['id_crypto_market']; ?>">✅</button>
                <?php else: ?>
                  <!-- Sinon, afficher bouton "ajouter" -->
                  <button class="watchlist-toggle" data-action="add" data-id="<?php echo $crypto['id_crypto_market']; ?>">❌</button>
                <?php endif; ?>
              </td>
            <?php endif; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Section combinant le widget TradingView et le formulaire d'ordre -->
  <div class="tradingwidget-order">
    <!-- Conteneur pour le widget TradingView -->
    <div id="tradingview-widget-container" class="tradingview-widget-container feature-trade"></div>

    <?php if (isset($_SESSION['user'])): ?>
      <!-- Formulaire pour passer un ordre, visible si connecté -->
      <div class="trading-order">
        <?php require_once RACINE . 'app/views/templates/tradingOrder.php'; ?>
      </div>
    <?php else: ?>
      <!-- Message invitant à se connecter pour trader -->
      <div class="trading-order trading-order2" style="background-image: url('<?= RACINE_URL ?>public/image/order-img.jpg');">
        <p>You must be logged in to trade</p>
      </div>
    <?php endif; ?>
  </div>

  <?php if (isset($_SESSION['user'])): ?>
    <!-- SECTION 3 : Affichage des positions en cours, si l'utilisateur est connecté -->
    <?php require_once RACINE . 'app/views/templates/positions.php'; ?>
  <?php endif; ?>

  <?php if (isset($_SESSION['user'])): ?>
    <!-- Lien vers la page Watchlist -->
    <div class="watchlist-link">
      <a href="index.php?page=watchlist">Go to Watchlist</a>
    </div>
  <?php endif; ?>

</div>

<!-- Déclaration d'une variable JS indiquant si l'utilisateur est connecté -->
<script>
    var isLoggedIn = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;
</script>

<!-- Chargement du script JavaScript gérant les interactions de la page Market -->
<script src="<?php echo RACINE_URL; ?>public/js/market.js"></script>

<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
