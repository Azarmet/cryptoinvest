<?php 
// Inclut l’en‑tête du back‑office (navigation, styles, etc.)
require_once RACINE . 'app/views/backoffice/headerback.php'; 
?>

<section class="faq-form-section">
    <!-- Titre de la section : selon qu’on édite ou ajoute une question -->
    <h1 class="section-title"><?= isset($faq) ? 'Edit' : 'Add' ?> a question</h1>

    <?php if (!empty($error)): ?>
        <!-- Affiche un message d’erreur si la validation a échoué -->
        <div class="alert alert-error">
            ⚠️ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Formulaire de création/édition de FAQ -->
    <form method="POST" action="" class="faq-form">
        <!-- Champ : Question de la FAQ -->
        <div class="form-group">
            <label for="question">Question:</label>
            <input type="text"
                   name="question"
                   id="question"
                   required
                   value="<?= isset($faq['question']) ? htmlspecialchars($faq['question']) : '' ?>">
        </div>

        <!-- Champ : Réponse de la FAQ -->
        <div class="form-group">
            <label for="reponse">Answer:</label>
            <textarea name="reponse"
                      id="reponse"
                      rows="6"
                      required><?= isset($faq['reponse']) ? htmlspecialchars($faq['reponse']) : '' ?></textarea>
        </div>

        <!-- Bouton de soumission : texte adapté selon action -->
        <input type="submit"
               class="btn-submit"
               value="<?= isset($faq) ? 'Update FAQ' : 'Create FAQ' ?>">
    </form>

    <!-- Lien de retour vers la liste des FAQ -->
    <div class="form-back-link">
        <a href="index.php?pageback=faq">← Back to list</a>
    </div>
</section>

<?php 
// Inclut le pied de page du back‑office (mentions légales, logout, etc.)
require_once RACINE . 'app/views/backoffice/footerback.php'; 
?>
