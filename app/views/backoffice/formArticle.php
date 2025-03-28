<?php require_once RACINE . "app/views/backoffice/headerback.php"; ?>

<h1><?= isset($article) ? "Modifier" : "Créer" ?> un article</h1>

<form method="POST" action="" enctype="multipart/form-data">
    <label for="titre">Titre :</label><br>
    <input type="text" name="titre" id="titre" required value="<?= isset($article) ? htmlspecialchars($article['titre']) : '' ?>"><br><br>

    <label for="contenu">Contenu :</label><br>
    <textarea name="contenu" id="contenu" rows="10" cols="60" required><?= isset($article) ? htmlspecialchars($article['contenu']) : '' ?></textarea><br><br>

    <input type="hidden" name="auteur" value="<?= $_SESSION['user']['id_utilisateur'] ?>"><br><br>
    <?php if (isset($article) && !empty($article['image'])): ?>
    <p>Image actuelle :</p>
    <img src="public/uploads/article/<?= htmlspecialchars($article['image']) ?>" alt="Image article" style="max-width: 200px;">
<?php endif; ?>

    <label for="image">Image :</label><br>
    <input type="file" name="image" id="image"><br><br>

    <label for="categorie">Catégorie :</label><br>
    <input type="text" name="categorie" id="categorie" required value="<?= isset($article) ? htmlspecialchars($article['categorie']) : '' ?>"><br><br>

    <label for="statut">Statut :</label><br>
    <select name="statut" id="statut">
        <option value="brouillon" <?= isset($article) && $article['statut'] == 'brouillon' ? 'selected' : '' ?>>Brouillon</option>
        <option value="publié" <?= isset($article) && $article['statut'] == 'publié' ? 'selected' : '' ?>>Publié</option>
    </select><br><br>

    <input type="submit" value="<?= isset($article) ? 'Mettre à jour' : 'Créer' ?> l'article">
</form>

<a href="index.php?pageback=learn">← Retour à la liste</a>
<?php require_once RACINE . "app/views/backoffice/footerback.php"; ?>