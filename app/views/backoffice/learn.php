<?php 
// Inclusion de l’en‑tête du back‑office (logo, navigation, styles)
require_once RACINE . 'app/views/backoffice/headerback.php'; 
?>

<section class="learn-back-section">
    <!-- Titre de la section de gestion des articles -->
    <h1 class="section-title">Article Management</h1>

    <!-- Lien vers le formulaire de création d’un nouvel article -->
    <a href="index.php?pageback=createArticle" class="btn btn-add-article">➕ New Article</a>

    <!-- Conteneur du tableau listant tous les articles -->
    <div class="table-container">
        <table class="learn-table">
            <thead>
                <tr>
                    <!-- En‑têtes de colonne -->
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
                    <!-- Affichage sécurisé de chaque donnée d’article -->
                    <td data-label="ID"><?= htmlspecialchars($article['id_article']) ?></td>
                    <td data-label="Title"><?= htmlspecialchars($article['titre']) ?></td>
                    <td data-label="Author"><?= htmlspecialchars($article['id_auteur']) ?></td>
                    <td data-label="Category"><?= htmlspecialchars($article['categorie']) ?></td>
                    <td data-label="Status"><?= htmlspecialchars($article['statut']) ?></td>
                    <td data-label="Date"><?= htmlspecialchars($article['date_publication']) ?></td>
                    <td data-label="Actions" class="article-actions">
                        <!-- Bouton d’édition renvoyant vers le formulaire d’édition -->
                        <a href="index.php?pageback=editArticle&id=<?= $article['id_article'] ?>"
                           class="action-btn edit"
                           title="Edit">
                            ✏️
                        </a>
                        <!-- Bouton de suppression, avec confirmation JS -->
                        <a href="index.php?pageback=deleteArticle&id=<?= $article['id_article'] ?>"
                           class="action-btn delete"
                           title="Delete"
                           onclick="return confirm('Delete this article?');">
                            🗑️
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php 
// Inclusion du pied de page du back‑office (mentions, logout, etc.)
require_once RACINE . 'app/views/backoffice/footerback.php'; 
?>
