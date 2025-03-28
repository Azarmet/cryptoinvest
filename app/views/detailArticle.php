<?php require_once RACINE . 'app/views/templates/header.php'; ?>


<h1><?= htmlspecialchars($article['titre']) ?></h1>
<img src="<?= RACINE_URL ."/public/uploads/article/" . $article['image']?>" alt="">
    <p><em><?= htmlspecialchars($article['categorie']) ?> - <?= $article['date_publication'] ?></em></p>
    <div><?= nl2br($article['contenu']) ?></div>
    <p><a href="index.php?page=learn">← Retour</a></p>






<?php require_once RACINE . 'app/views/templates/footer.php'; ?>