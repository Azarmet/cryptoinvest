<?php
namespace App\Controllers;

use App\Models\CryptoMarket;

function showMarket() {
    $cryptoModel = new CryptoMarket();
    // Optionnel : mettre à jour les données lors du chargement initial
    $cryptoModel->updateFromBinance();
    $cryptos = $cryptoModel->getAll();
    require_once RACINE . "app/views/market.php";
}

function refreshMarket() {
    header('Content-Type: application/json');
    $cryptoModel = new CryptoMarket();
    // Mettre à jour les données en temps réel via l'API Binance
    $cryptoModel->updateFromBinance();
    $cryptos = $cryptoModel->getAll();
    echo json_encode($cryptos);
}
?>
