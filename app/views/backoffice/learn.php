<?php require_once RACINE . 'app/views/backoffice/headerback.php'; ?>

<h1>Gestion des Articles</h1>

<a href="index.php?pageback=createArticle" class="btn btn-primary">➕ Nouvel Article</a>
<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Auteur</th>
            <th>Catégorie</th>
            <th>Statut</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($articles as $article): ?>
        <tr>
            <td><?= htmlspecialchars($article['id_article']) ?></td>
            <td><?= htmlspecialchars($article['titre']) ?></td>
            <td><?= htmlspecialchars($article['id_auteur']) ?></td>
            <td><?= htmlspecialchars($article['categorie']) ?></td>
            <td><?= htmlspecialchars($article['statut']) ?></td>
            <td><?= htmlspecialchars($article['date_publication']) ?></td>
            <td>
                <a href="index.php?pageback=editArticle&id=<?= $article['id_article'] ?>">✏️ Modifier</a> |
                <a href="index.php?pageback=deleteArticle&id=<?= $article['id_article'] ?>" onclick="return confirm('Supprimer cet article ?');">🗑️ Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php require_once RACINE . 'app/views/backoffice/footerback.php'; ?>