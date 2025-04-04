<?php
// index.php (tout en haut)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    // Définir la racine du projet
    define('RACINE', __DIR__ . '/');
    // define('RACINE_URL', '/lucas-roirand/CryptoInvestMVC/'); // ou simplement '/' si c'est à la racine du domaine
    define('RACINE_URL', '/php_project/CryptoInvestMVC/'); // ou simplement '/' si c'est à la racine du domaine
    // Charger l'autoloader de Composer
    require_once RACINE . 'vendor/autoload.php';

    // Inclure le routeur
    require_once RACINE . "app/routeur.php";

    // Appeler la fonction du routeur
    
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    };
    
    if(isset($_SESSION['role']) &&  $_SESSION['role'] === 'admin'){
        routeurBack();
    }else{
    routeur();
    }
?>
