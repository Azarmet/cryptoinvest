<?php
// Démarre la session si elle n'est pas déjà active,
// pour gérer l'authentification et afficher les liens appropriés.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Rend la mise en page responsive sur les appareils mobiles -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Jeu de caractères utilisé -->
    <meta charset="UTF-8">
    <!-- Titre affiché dans l’onglet du navigateur -->
    <title>CryptoInvest - Backoffice</title>
    <!-- Lien vers la feuille de styles dédiée au back‑office -->
    <link rel="stylesheet" href="<?= RACINE_URL . 'public/css/backoffice.css'?>">
</head>
<body>
    <!-- En‑tête fixe du back‑office contenant le logo et la navigation -->
    <header class="back-header">
        <div class="header-left">
            <!-- Logo ou titre de l’administration -->
            <h1 class="logo">CryptoInvest Admin</h1>
        </div>
        <nav class="header-nav">
            <!-- Menu de navigation principal du back‑office -->
            <ul>
                <li><a href="index.php?pageback=home">Home</a></li>
                <li><a href="index.php?pageback=faq">FAQ</a></li>
                <li><a href="index.php?pageback=learn">Learn</a></li>
                <li><a href="index.php?pageback=market">Market</a></li>
                <li><a href="index.php?pageback=users">Users</a></li>
            </ul>
        </nav>
    </header>
    <!-- Contenu principal de la page, à compléter par chaque vue spécifique -->
    <main>
