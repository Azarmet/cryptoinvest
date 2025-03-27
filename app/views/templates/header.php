<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>CryptoInvest</title>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php?page=home">Home</a></li>
                <li><a href="index.php?page=faq">FAQ</a></li>
                <li><a href="index.php?page=learn">Learn</a></li>
                <?php if(isset($_SESSION['user'])): ?>
                <li><a href="index.php?page=dashboard">Dashboard</a></li>
                <?php endif; ?>
                <li><a href="index.php?page=market">Market</a></li>
                <li><a href="index.php?page=leaderboard">Leaderboard</a></li>
                <?php if(isset($_SESSION['user'])): ?>
                    <!-- L'utilisateur est connecté : afficher le pseudo et un lien vers le profil -->
                    <li><img src="<?php echo htmlspecialchars($_SESSION['user']['image_profil']); ?>" alt="Image de Profil" width="30"><a href="index.php?page=profil"><?php echo htmlspecialchars($_SESSION['user']['pseudo']); ?></a></li>
                <?php else: ?>
                    <!-- L'utilisateur n'est pas connecté : afficher le lien de connexion -->
                    <li><a href="index.php?page=login">Connexion</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>