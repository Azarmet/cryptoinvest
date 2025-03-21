<?php
    // Définir la racine du projet
    define('RACINE', __DIR__ . '/');

    // Charger l'autoloader de Composer
    require_once RACINE . 'vendor/autoload.php';

    // Inclure le routeur
    require_once RACINE . "app/routeur.php";

    // Appeler la fonction du routeur
    routeur();
?>
