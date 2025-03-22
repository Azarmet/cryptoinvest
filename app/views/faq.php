<?php require_once RACINE . "app/views/templates/header.php"; ?>

<h2>Foire aux Questions</h2>
<!-- Barre de recherche -->
<input type="text" id="faq-search" placeholder="Rechercher dans la FAQ...">

<!-- Conteneur pour afficher les FAQ -->
<div id="faq-results">
    <?php if (!empty($faqs)): ?>
        <?php foreach($faqs as $faq): ?>
            <div class="faq-item">
                <h3><?php echo htmlspecialchars($faq['question']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($faq['reponse'])); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun résultat trouvé.</p>
    <?php endif; ?>
</div>


<script src="<?php echo RACINE_URL; ?>public/js/faq.js"></script>

<?php require_once RACINE . "app/views/templates/footer.php"; ?>
