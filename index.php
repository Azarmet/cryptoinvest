<?php
    // Définir la racine du projet
    define('RACINE', __DIR__ . '/');
    define('RACINE_URL', '/php_project/CryptoInvestMVC/'); // ou simplement '/' si c'est à la racine du domaine
    // Charger l'autoloader de Composer
    require_once RACINE . 'vendor/autoload.php';

    // Inclure le routeur
    require_once RACINE . "app/routeur.php";

    // Appeler la fonction du routeur
    routeur();
?>
