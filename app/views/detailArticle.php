<?php require_once RACINE . 'app/views/templates/header.php'; ?>

<div class="detail-article-container">
    <h1><?= htmlspecialchars($article['titre']) ?></h1>
    <div class="article-meta">
        <img src="<?= RACINE_URL ."/public/uploads/article/" . $article['image']?>" alt="<?= htmlspecialchars($article['titre']) ?>" class="article-image">
        <p class="article-info"><em><?= htmlspecialchars($article['categorie']) ?> - <?= $article['date_publication'] ?></em></p>
    </div>
    <div class="article-content">
        <?= nl2br($article['contenu']) ?>
    </div>
    <p class="back-link"><a href="index.php?page=learn">← Retour</a></p>
</div>

<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
