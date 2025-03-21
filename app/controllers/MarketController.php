<?php
namespace App\Controllers;

use App\Models\CryptoMarket;

function showMarket() {
    $cryptoModel = new CryptoMarket();
    $cryptos = $cryptoModel->getAll(); // Méthode qui récupère toutes les entrées de la table
    require_once RACINE . "app/views/market.php";
}

function refreshMarket() {
    header('Content-Type: application/json');
    $cryptoModel = new CryptoMarket();
    $cryptos = $cryptoModel->getAll();
    echo json_encode($cryptos);
}
?>
