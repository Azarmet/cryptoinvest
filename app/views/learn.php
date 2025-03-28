<?php require_once RACINE . 'app/views/templates/header.php'; ?>

<h2>Learn - Articles</h2>

<!-- Onglets de catégories -->
<div id="category-tabs" style="margin-bottom: 15px;">
    <button class="tab-button" data-category="Tous">Tous</button>
    <button class="tab-button" data-category="Tutoriels">Tutoriels</button>
    <button class="tab-button" data-category="Crypto News">Crypto News</button>
    <button class="tab-button" data-category="Analyses">Analyses</button>
    <button class="tab-button" data-category="Guide">Guide</button>
</div>

<!-- Barre de recherche -->
<div style="margin-bottom: 15px;">
    <input type="text" id="learn-search" placeholder="Rechercher un sujet..." style="width: 300px; padding: 5px;">
    <button id="search-button">Rechercher</button>
</div>

<!-- Zone d'affichage des articles -->
<div id="articles-container">
    <!-- Les articles seront injectés ici en AJAX -->
</div>

<!-- Container vide pour la pagination -->
<div id="pagination" style="margin-top: 15px;">
    <!-- La pagination sera construite en JavaScript -->
</div>
<script src="<?php echo RACINE_URL; ?>public/js/learn.js"></script>


<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
