<?php

    // Définir la racine du projet
    define('RACINE', __DIR__ . '/');
    define('RACINE_URL', '/lucas-roirand/CryptoInvestMVC/'); 
    //define('RACINE_URL', '/php_project/CryptoInvestMVC/'); // 
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
