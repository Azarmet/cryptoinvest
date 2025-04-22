<?php

    // Définir la racine du projet
    define('RACINE', __DIR__ . '/');
    define('RACINE_URL', rtrim(dirname($_SERVER['SCRIPT_NAME'], 2), '/') . '/CryptoInvestMVC/');

    // Charger l'autoloader de Composer
    require_once RACINE . 'vendor/autoload.php';

    // Inclure le routeur
    require_once RACINE . "app/routeur.php";
    // Appeler la fonction du routeur
    
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    };
    
    if(isset($_SESSION['role']) &&  $_SESSION['role'] === 'admin' && $_SESSION['unique'] ==='55551564365184949565356487'){
        routeurBack();
    }else{
    routeur();
    }
?>
