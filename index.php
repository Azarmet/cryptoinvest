<?php
    // Définition de la racine du projet (pour inclure plus facilement les fichiers)
    define("RACINE", __DIR__ . "/");

    // Inclure le routeur
    require_once RACINE . "app/routeur.php";

    // On appelle la fonction principale du routeur
    routeur();
?>