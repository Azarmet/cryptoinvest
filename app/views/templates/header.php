<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CryptoInvest <?php if (isset($_GET['page']) && $_GET['page'] !== 'home'): echo ucfirst(htmlspecialchars($_GET['page'], ENT_QUOTES, 'UTF-8')); endif; ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/webp" href="<?= RACINE_URL ?>public/image/logo.webp">
    <!-- Icones FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" >
    <!-- Feuille de style principale -->
    <link rel="stylesheet" href="<?= RACINE_URL . 'public/css/style.css'?>">
</head>
<body class="<?= isset($_GET['page']) ? 'page-' . $_GET['page'] : '' ?>">

<div class="site-wrapper">
    <header class="site-header">
        <nav class="navbar" id="navbar">

            <!-- Logo et nom de la marque -->
            <div class="logo">
                <a href="index.php?page=home">
                    <img src="<?= RACINE_URL . 'public/image/logo.webp'?>" alt="CryptoInvest logo" class="logo-header">
                    <span class="brand-name">Crypto<span class="orange">Invest</span></span>
                </a>
            </div>

            <!-- Bouton burger pour petits écrans -->
            <div class="burger" id="burger">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>

            <!-- Menu de navigation -->
            <?php
            // Détermination de la page active pour le style
            $currentPage = $_GET['page'] ?? 'home';
            ?>
            <ul class="nav-links">
                <li><a href="index.php?page=home" class="<?= $currentPage === 'home' ? 'active' : '' ?>">Home</a></li>
                <li><a href="index.php?page=faq" class="<?= $currentPage === 'faq' ? 'active' : '' ?>">FAQ</a></li>
                <li><a href="index.php?page=learn" class="<?= $currentPage === 'learn' ? 'active' : '' ?>">Learn</a></li>

                <?php if (isset($_SESSION['user'])): ?>
                    <!-- Lien vers le dashboard si connecté -->
                    <li><a href="index.php?page=dashboard" class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>">Dashboard</a></li>
                <?php endif; ?>

                <li><a href="index.php?page=market" class="<?= $currentPage === 'market' ? 'active' : '' ?>">Market</a></li>
                <li><a href="index.php?page=leaderboard" class="<?= $currentPage === 'leaderboard' ? 'active' : '' ?>">Leaderboard</a></li>

                <?php if (isset($_SESSION['user'])): ?>
                    <!-- Profil et déconnexion -->
                    <li class="profile">
                        <img src="<?= htmlspecialchars($_SESSION['user']['image_profil']) ?>" alt="Profile">
                        <a href="index.php?page=profil" class="<?= $currentPage === 'profil' ? 'active' : '' ?>">
                            <?= htmlspecialchars($_SESSION['user']['pseudo']) ?>
                        </a>
                    </li>
                <?php else: ?>
                    <!-- Lien vers page de connexion si non connecté -->
                    <li><a href="index.php?page=login" class="<?= $currentPage === 'login' ? 'active' : '' ?>">Login</a></li>
                <?php endif; ?>
            </ul>

        </nav>
    </header>
    <main>
    <!-- Contenu principal injecté ici -->
    <script src="<?php echo RACINE_URL; ?>public/js/burger.js"></script>
