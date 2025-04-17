<?php 
// Inclut l’en‑tête commun du back‑office (navigation, CSS, etc.)
require_once RACINE . 'app/views/backoffice/headerback.php'; 
?>

<section class="article-form-section">
    <!-- Titre de la section : selon qu’on édite ou crée un article -->
    <h1 class="section-title"><?= isset($article) ? 'Edit' : 'Create' ?> an article</h1>

    <?php if (!empty($error)): ?>
        <!-- Affiche un message d’erreur si la validation a échoué -->
        <div class="alert alert-error">
            ⚠️ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Formulaire de création/édition d’article (multipart pour l’upload d’image) -->
    <form method="POST" action="" enctype="multipart/form-data" class="article-form">
        <!-- Champ : Titre de l’article -->
        <div class="form-group">
            <label for="titre">Title:</label>
            <input type="text"
                   name="titre"
                   id="titre"
                   required
                   value="<?= isset($article['titre']) ? htmlspecialchars($article['titre']) : '' ?>">
        </div>

        <!-- Champ : Contenu de l’article -->
        <div class="form-group">
            <label for="contenu">Content:</label>
            <textarea name="contenu"
                      id="contenu"
                      rows="10"
                      required><?= isset($article['contenu']) ? htmlspecialchars($article['contenu']) : '' ?></textarea>
        </div>

        <!-- Champ caché : Identifiant de l’auteur (utilisateur connecté) -->
        <input type="hidden" name="auteur" value="<?= $_SESSION['user']['id_utilisateur'] ?>">

        <?php if (isset($article['image']) && !empty($article['image'])): ?>
            <!-- Affiche l’image actuelle si un visuel existe déjà -->
            <div class="form-group">
                <label>Current image:</label>
                <img src="public/uploads/article/<?= htmlspecialchars($article['image']) ?>"
                     alt="Article image"
                     class="image-preview">
            </div>
        <?php endif; ?>

        <!-- Champ : Sélection d’un nouveau fichier image -->
        <div class="form-group">
            <label for="image">Image:</label>
            <input type="file"
                   name="image"
                   id="image"
                   accept="image/*">
        </div>

        <!-- Conteneur de prévisualisation de l’image uploadée -->
        <div class="form-group" id="preview-container" style="display: none;">
            <label>Preview:</label>
            <img id="preview"
                 src="#"
                 alt="Image preview"
                 class="image-preview">
        </div>

        <!-- Champ : Sélection de la catégorie parmi les options définies -->
        <div class="form-group">
            <label for="categorie">Category:</label>
            <select name="categorie" id="categorie" required>
                <?php
                $categories = ['Analysis', 'Guide', 'Tutorials', 'Crypto News'];
                $selected = isset($article['categorie']) ? $article['categorie'] : '';
                foreach ($categories as $cat):
                ?>
                    <option value="<?= $cat ?>"
                        <?= $selected === $cat ? 'selected' : '' ?>>
                        <?= $cat ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Champ : Choix du statut (brouillon ou publié) -->
        <div class="form-group">
            <label for="statut">Status:</label>
            <select name="statut" id="statut">
                <option value="brouillon"
                    <?= isset($article['statut']) && $article['statut'] == 'brouillon' ? 'selected' : '' ?>>
                    Draft
                </option>
                <option value="publié"
                    <?= isset($article['statut']) && $article['statut'] == 'publié' ? 'selected' : '' ?>>
                    Published
                </option>
            </select>
        </div>

        <!-- Bouton de soumission : texte adapté selon action -->
        <input type="submit"
               class="btn-submit"
               value="<?= isset($article) ? 'Update' : 'Create' ?> the article">
    </form>

    <!-- Lien de retour vers la liste des articles -->
    <div class="form-back-link">
        <a href="index.php?pageback=learn">← Back to list</a>
    </div>
</section>

<!-- Inclusion du script gérant la prévisualisation de l’image -->
<script src="<?php echo RACINE_URL; ?>public/js/formarticle.js"></script>

<?php 
// Inclut le pied de page du back‑office
require_once RACINE . 'app/views/backoffice/footerback.php'; 
?>
