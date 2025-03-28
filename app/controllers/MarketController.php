<?php
namespace App\Controllers;

use App\Models\CryptoMarket;
use App\Models\CryptoTrans;

function showMarket()
{
    $cryptoModel = new CryptoMarket();
    // Optionnel : mettre à jour les données lors du chargement initial
    $cryptoModel->updateFromBinance();
    $categorie = $_GET['categorie'] ?? 'top';
    $cryptos = $cryptoModel->getAllFromCat($categorie);

    require_once RACINE . 'app/views/market.php';
}

function refreshMarket()
{
    header('Content-Type: application/json');
    $cryptoModel = new CryptoMarket();
    // Mettre à jour les données en temps réel via l'API Binance
    $cryptoModel->updateFromBinance();
    $cryptos = $cryptoModel->getAll();
    $categorie = $_GET['categorie'] ?? 'top';

    if ($categorie === 'all') {
        $cryptos = $cryptoModel->getAll();
    } else {
        $cryptos = $cryptoModel->getAllFromCat($categorie);
    }

    echo json_encode($cryptos);
    exit;
}

function showBackMarket()
{
    $marketModel = new CryptoMarket();
    $transModel = new CryptoTrans();

    $marketCryptos = $marketModel->getAll();
    $transCryptos = $transModel->getAll();

    require_once RACINE . 'app/views/backoffice/market.php';
}

function createCryptoMarket()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $code = trim($_POST['code']);
        $categorie = trim($_POST['categorie']);

        if (!empty($code) && !empty($categorie)) {
            $model = new CryptoMarket();
            $model->createCrypto($code, $categorie);
            header('Location: index.php?pageback=market&success=1');
            exit;
        } else {
            $error = 'Veuillez remplir tous les champs.';
        }
    }

    require_once RACINE . 'app/views/backoffice/formCryptoMarket.php';
}

function deleteCryptoMarket($id)
{
    $model = new CryptoMarket();
    $model->deleteCrypto($id);
    header('Location: index.php?pageback=market&success=2');
    exit;
}

function createCryptoTrans()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $code = trim($_POST['code']);

        if (!empty($code)) {
            $model = new CryptoTrans();
            $model->createCrypto($code);
            header('Location: index.php?pageback=market&success=3');
            exit;
        } else {
            $error = 'Le code de la crypto est obligatoire.';
        }
    }

    require_once RACINE . 'app/views/backoffice/formCryptoTrans.php';
}

function deleteCryptoTrans($id)
{
    $model = new CryptoTrans();
    $model->deleteCrypto($id);
    header('Location: index.php?pageback=market&success=4');
    exit;
}

?>
