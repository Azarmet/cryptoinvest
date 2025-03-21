<?php
// test-connection.php

// Définir la racine du projet en remontant d'un niveau (car ce fichier est dans public)
define('RACINE', __DIR__ . '/../');

// Charger l'autoloader de Composer
require_once RACINE . 'vendor/autoload.php';

use App\Models\Database;

// Récupérer l'instance PDO via notre objet Database
$db = Database::getInstance()->getConnection();

if ($db) {
    echo "Connexion à la base de données réussie !";
} else {
    echo "Erreur de connexion à la base de données.";
}
?>
