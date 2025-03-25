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
    $categorie = $_GET['categorie'] ?? 'all';

    if ($categorie === 'all') {
        $cryptos = $cryptoModel->getAll();
    } else {
        $cryptos = $cryptoModel->getAllFromCat($categorie);
    }
    

    echo json_encode($cryptos);
    exit;

}



?>
