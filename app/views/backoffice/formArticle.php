<?php require_once RACINE . 'app/views/backoffice/headerback.php'; ?>

<section class="article-form-section">
    <h1 class="section-title"><?= isset($article) ? 'Modifier' : 'Créer' ?> un article</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            ⚠️ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data" class="article-form">
        <div class="form-group">
            <label for="titre">Titre :</label>
            <input type="text" name="titre" id="titre" required value="<?= isset($article['titre']) ? htmlspecialchars($article['titre']) : '' ?>">
        </div>

        <div class="form-group">
            <label for="contenu">Contenu :</label>
            <textarea name="contenu" id="contenu" rows="10" required><?= isset($article['contenu']) ? htmlspecialchars($article['contenu']) : '' ?></textarea>
        </div>

        <input type="hidden" name="auteur" value="<?= $_SESSION['user']['id_utilisateur'] ?>">

        <?php if (isset($article['image']) && !empty($article['image'])): ?>
            <div class="form-group">
                <label>Image actuelle :</label>
                <img src="public/uploads/article/<?= htmlspecialchars($article['image']) ?>" alt="Image article" class="image-preview">
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="image">Image :</label>
            <input type="file" name="image" id="image" accept="image/*">
        </div>

        <div class="form-group" id="preview-container" style="display: none;">
            <label>Aperçu :</label>
            <img id="preview" src="#" alt="Aperçu de l'image" class="image-preview">
        </div>

        <div class="form-group">
            <label for="categorie">Catégorie :</label>
            <select name="categorie" id="categorie" required>
                <?php
                $categories = ['Analyses', 'Guide', 'Tutoriels', 'Crypto News'];
                $selected = isset($article['categorie']) ? $article['categorie'] : '';
                foreach ($categories as $cat):
                ?>
                    <option value="<?= $cat ?>" <?= $selected === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="statut">Statut :</label>
            <select name="statut" id="statut">
                <option value="brouillon" <?= isset($article['statut']) && $article['statut'] == 'brouillon' ? 'selected' : '' ?>>Brouillon</option>
                <option value="publié" <?= isset($article['statut']) && $article['statut'] == 'publié' ? 'selected' : '' ?>>Publié</option>
            </select>
        </div>

        <input type="submit" class="btn-submit" value="<?= isset($article) ? 'Mettre à jour' : 'Créer' ?> l'article">
    </form>

    <div class="form-back-link">
        <a href="index.php?pageback=learn">← Retour à la liste</a>
    </div>
</section>

<script src="<?php echo RACINE_URL; ?>public/js/formarticle.js"></script>
<?php require_once RACINE . 'app/views/backoffice/footerback.php'; ?>
