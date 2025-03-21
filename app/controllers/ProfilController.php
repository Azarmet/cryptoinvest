<?php

namespace App\Controllers;

use App\Models\User;

function showProfil(){
    require_once RACINE . "app/views/profil.php";
}

function logout() {
    // Démarrer la session si elle n'est pas déjà lancée
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // Vider le tableau de session
    $_SESSION = [];
    // Détruire la session
    session_destroy();
    // Rediriger vers la page d'accueil (ou la page de connexion)
    header("Location: index.php?page=home");
    exit();
}

?>