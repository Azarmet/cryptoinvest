<?php require_once RACINE . 'app/views/backoffice/headerback.php'; ?>

<section class="learn-back-section">
    <h1 class="section-title">Gestion des Articles</h1>

    <a href="index.php?pageback=createArticle" class="btn btn-add-article">➕ Nouvel Article</a>

    <div class="table-container">
        <table class="learn-table">
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
                    <td data-label="ID"><?= htmlspecialchars($article['id_article']) ?></td>
                    <td data-label="Titre"><?= htmlspecialchars($article['titre']) ?></td>
                    <td data-label="Auteur"><?= htmlspecialchars($article['id_auteur']) ?></td>
                    <td data-label="Catégorie"><?= htmlspecialchars($article['categorie']) ?></td>
                    <td data-label="Statut"><?= htmlspecialchars($article['statut']) ?></td>
                    <td data-label="Date"><?= htmlspecialchars($article['date_publication']) ?></td>
                    <td data-label="Actions" class="article-actions">
                        <a href="index.php?pageback=editArticle&id=<?= $article['id_article'] ?>" class="action-btn edit" title="Modifier">✏️</a>
                        <a href="index.php?pageback=deleteArticle&id=<?= $article['id_article'] ?>" class="action-btn delete" title="Supprimer" onclick="return confirm('Supprimer cet article ?');">🗑️</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require_once RACINE . 'app/views/backoffice/footerback.php'; ?>
