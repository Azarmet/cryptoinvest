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
                <li><a href="index.php?pageback=home">Home</a></li>
                <li><a href="index.php?pageback=faq">FAQ Manager</a></li>
                <li><a href="index.php?pageback=learn">Learn Manager</a></li>
                <li><a href="index.php?pageback=market">Market Manager</a></li>
                <li><a href="index.php?pageback=users">Users Manager</a></li>
            </ul>
        </nav>
    </header>
    <main>