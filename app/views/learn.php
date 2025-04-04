<?php require_once RACINE . 'app/views/templates/header.php'; ?>
<link rel="stylesheet" href="<?= RACINE_URL . 'public/css/learn.css'?>">

<div class="learn-container">
  <h2>Learn - Articles</h2>

  <!-- Onglets de catégories -->
  <div id="category-tabs" class="tabs-container">
      <button class="tab-button" data-category="Tous">Tous</button>
      <button class="tab-button" data-category="Tutoriels">Tutoriels</button>
      <button class="tab-button" data-category="Crypto News">Crypto News</button>
      <button class="tab-button" data-category="Analyses">Analyses</button>
      <button class="tab-button" data-category="Guide">Guide</button>
  </div>

  <!-- Barre de recherche -->
  <div class="search-container">
      <input type="text" id="learn-search" class="search-input" placeholder="Rechercher un sujet...">
      <button id="search-button" class="search-button">Rechercher</button>
  </div>

  <!-- Zone d'affichage des articles -->
  <div id="articles-container" class="articles-container">
      <!-- Les articles seront injectés ici en AJAX -->
  </div>

  <!-- Container pour la pagination -->
  <div id="pagination" class="pagination-container">
      <!-- La pagination sera construite en JavaScript -->
  </div>
</div>

<script src="<?php echo RACINE_URL; ?>public/js/learn.js"></script>

<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
