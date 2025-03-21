<?php require_once RACINE . "app/views/templates/header.php"; ?>

<h1>Profil de <?php echo htmlspecialchars($_SESSION['user']['pseudo']); ?></h1>
<!-- Contenu du profil -->

<p>
    <a href="index.php?page=logout">Déconnexion</a>
</p>

<?php require_once RACINE . "app/views/templates/footer.php"; ?>
