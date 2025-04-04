<?php require_once RACINE . 'app/views/templates/header.php'; ?>

<section class="faq-wrapper">
    <h2 class="faq-title">Foire aux Questions</h2>

    <div class="faq-search-container">
        <input type="text" id="faq-search" class="faq-search-input" placeholder="🔍 Rechercher une question...">
    </div>

    <div id="faq-results" class="faq-results">
        <?php if (!empty($faqs)): ?>
            <?php foreach ($faqs as $faq): ?>
                <div class="faq-item">
                    <button class="faq-question">
                        <?php echo htmlspecialchars($faq['question']); ?>
                        <span class="faq-toggle-icon">+</span>
                    </button>
                    <div class="faq-answer">
                        <p><?php echo nl2br(htmlspecialchars($faq['reponse'])); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-results">Aucun résultat trouvé.</p>
        <?php endif; ?>
    </div>
</section>

<script src="<?php echo RACINE_URL; ?>public/js/faq.js"></script>
<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
