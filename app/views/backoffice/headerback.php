<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>CryptoInvest - Backoffice</title>
    <link rel="stylesheet" href="<?= RACINE_URL . 'public/css/backoffice.css'?>">
</head>
<body>
    <header class="back-header">
        <div class="header-left">
            <h1 class="logo">CryptoInvest Admin</h1>
        </div>
        <nav class="header-nav">
            <ul>
                <li><a href="index.php?pageback=home">Home</a></li>
                <li><a href="index.php?pageback=faq">FAQ</a></li>
                <li><a href="index.php?pageback=learn">Learn</a></li>
                <li><a href="index.php?pageback=market">Market</a></li>
                <li><a href="index.php?pageback=users">Users</a></li>
            </ul>
        </nav>
    </header>
    <main>
