<?php require_once RACINE . 'app/views/backoffice/headerback.php'; ?>

<section class="faq-form-section">
    <h1 class="section-title"><?= isset($faq) ? 'Modifier' : 'Ajouter' ?> une question</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            ⚠️ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="" class="faq-form">
        <div class="form-group">
            <label for="question">Question :</label>
            <input type="text" name="question" id="question" required
                   value="<?= isset($faq['question']) ? htmlspecialchars($faq['question']) : '' ?>">
        </div>

        <div class="form-group">
            <label for="reponse">Réponse :</label>
            <textarea name="reponse" id="reponse" rows="6" required><?= isset($faq['reponse']) ? htmlspecialchars($faq['reponse']) : '' ?></textarea>
        </div>

        <input type="submit" class="btn-submit" value="<?= isset($faq) ? 'Mettre à jour' : 'Créer' ?> la FAQ">
    </form>

    <div class="form-back-link">
        <a href="index.php?pageback=faq">← Retour à la liste</a>
    </div>
</section>

<?php require_once RACINE . 'app/views/backoffice/footerback.php'; ?>
