<?php require_once RACINE . 'app/views/templates/header.php'; ?>
<link rel="stylesheet" href="<?= RACINE_URL . 'public/css/faq.css'?>">

<div class="faq-container">
    <h2>Foire aux Questions</h2>

    <!-- Barre de recherche -->
    <div class="faq-search-container">
        <input type="text" id="faq-search" class="faq-search-input" placeholder="Rechercher dans la FAQ...">
    </div>

    <!-- Conteneur pour afficher les FAQ -->
    <div id="faq-results" class="faq-results">
        <?php if (!empty($faqs)): ?>
            <?php foreach ($faqs as $faq): ?>
                <div class="faq-item">
                    <h3 class="faq-question"><?php echo htmlspecialchars($faq['question']); ?></h3>
                    <p class="faq-answer"><?php echo nl2br(htmlspecialchars($faq['reponse'])); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun résultat trouvé.</p>
        <?php endif; ?>
    </div>
</div>

<script src="<?php echo RACINE_URL; ?>public/js/faq.js"></script>

<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
