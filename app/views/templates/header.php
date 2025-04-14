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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?= RACINE_URL . 'public/css/style.css'?>">
    <link rel="stylesheet" href="<?= RACINE_URL . "public/css/page/home.css"?>">
    <link rel="stylesheet" href="<?= RACINE_URL . 'public/css/templates/header.css'?>">
<?php if(isset($_GET['page']) && $_GET['page']==='leaderboard'):?>    
    
<?php elseif((isset($_GET['page'])) && $_GET['page'] !== 'home'): ?>
    <link rel="stylesheet" href="<?= RACINE_URL . "public/css/page/" . $_GET["page"] .".css"?>">

<?php else: ?>
    <link rel="stylesheet" href="<?= RACINE_URL . "public/css/page/home.css"?>">
<?php endif; ?>
    <!-- Import des polices -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&family=Roboto:wght@700&display=swap" rel="stylesheet">
</head>
<body class="<?= isset($_GET['page']) ? 'page-' . $_GET['page'] : '' ?>">

<div class="site-wrapper">
    <header class="site-header">
        <nav class="navbar" id="navbar">
        <div class="logo">
  <a href="index.php?page=home">
    <img src="<?= RACINE_URL . 'public/image/logo.png'?>" alt="logo crypto invest" class="logo-header">
    <span class="brand-name">Crypto<span class="orange">Invest</span></span>
  </a>
</div>
            <!-- Bouton burger -->
            <div class="burger" id="burger">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
            <!-- Menu de navigation -->
            <?php
$currentPage = $_GET['page'] ?? 'home';
?>
            <ul class="nav-links">
                <li><a href="index.php?page=home" class="<?= $currentPage === 'home' ? 'active' : '' ?>">Home</a></li>
                <li><a href="index.php?page=faq" class="<?= $currentPage === 'faq' ? 'active' : '' ?>">FAQ</a></li>
                <li><a href="index.php?page=learn" class="<?= $currentPage === 'learn' ? 'active' : '' ?>">Learn</a></li>

                <?php if (isset($_SESSION['user'])): ?>
                    <li><a href="index.php?page=dashboard" class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>">Dashboard</a></li>
                <?php endif; ?>

                <li><a href="index.php?page=market" class="<?= $currentPage === 'market' ? 'active' : '' ?>">Market</a></li>
                <li><a href="index.php?page=leaderboard" class="<?= $currentPage === 'leaderboard' ? 'active' : '' ?>">Leaderboard</a></li>

                <?php if (isset($_SESSION['user'])): ?>
                    <li class="profile">
                        <img src="<?= htmlspecialchars($_SESSION['user']['image_profil']) ?>" alt="Profil">
                        <a href="index.php?page=profil" class="<?= $currentPage === 'profil' ? 'active' : '' ?>">
                            <?= htmlspecialchars($_SESSION['user']['pseudo']) ?>
                        </a>
                    </li>
                <?php else: ?>
                    <li><a href="index.php?page=login" class="<?= $currentPage === 'login' ? 'active' : '' ?>">Connexion</a></li>
                <?php endif; ?>
            </ul>

        </nav>
    </header>
    <main>
    <!-- Votre contenu principal -->
    <script>
        // Assure l'exécution une fois le DOM chargé
        document.addEventListener('DOMContentLoaded', function() {
            const burger = document.getElementById('burger');
            const navLinks = document.querySelector('.nav-links');
            burger.addEventListener('click', function() {
                navLinks.classList.toggle('nav-active');
                burger.classList.toggle('toggle');
            });
        });
    </script>
