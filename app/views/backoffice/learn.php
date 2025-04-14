<?php require_once RACINE . 'app/views/backoffice/headerback.php'; ?>

<section class="learn-back-section">
    <h1 class="section-title">Article Management</h1>

    <a href="index.php?pageback=createArticle" class="btn btn-add-article">➕ New Article</a>

    <div class="table-container">
        <table class="learn-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($articles as $article): ?>
                <tr>
                    <td data-label="ID"><?= htmlspecialchars($article['id_article']) ?></td>
                    <td data-label="Title"><?= htmlspecialchars($article['titre']) ?></td>
                    <td data-label="Author"><?= htmlspecialchars($article['id_auteur']) ?></td>
                    <td data-label="Category"><?= htmlspecialchars($article['categorie']) ?></td>
                    <td data-label="Status"><?= htmlspecialchars($article['statut']) ?></td>
                    <td data-label="Date"><?= htmlspecialchars($article['date_publication']) ?></td>
                    <td data-label="Actions" class="article-actions">
                        <a href="index.php?pageback=editArticle&id=<?= $article['id_article'] ?>" class="action-btn edit" title="Edit">✏️</a>
                        <a href="index.php?pageback=deleteArticle&id=<?= $article['id_article'] ?>" class="action-btn delete" title="Delete" onclick="return confirm('Delete this article?');">🗑️</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require_once RACINE . 'app/views/backoffice/footerback.php'; ?>
