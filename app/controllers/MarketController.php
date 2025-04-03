<?php
namespace App\Controllers;

use App\Models\CryptoMarket;
use App\Models\CryptoTrans;
use App\Models\Watchlist;
use App\Models\Transaction;

function showMarket()
{
    $cryptoModel = new CryptoMarket();
    // Optionnel : mettre à jour les données lors du chargement initial
    $cryptoModel->updateFromBinance();
    $categorie = $_GET['categorie'] ?? 'top';
    $cryptos = $cryptoModel->getAllFromCat($categorie);
    $cryptosTrans = cryptoTrans();

    // Ajout de la logique watchlist si utilisateur connecté
    if (isset($_SESSION['user'])) {
        $watchlistModel = new Watchlist();
        $watchlist = $watchlistModel->getWatchlist($_SESSION['user']['id_utilisateur']);
        $watchlistIds = array_map(function($crypto) {
            return $crypto['id_crypto_market'];
        }, $watchlist);

        foreach ($cryptos as &$crypto) {
            $crypto['in_watchlist'] = in_array($crypto['id_crypto_market'], $watchlistIds);
        }
    }

    require_once RACINE . 'app/views/market.php';
}


function cryptoTrans()
{
    $transactionnModel = new Transaction();
    $cryptos = $transactionnModel->getCryptoTrans();
    return $cryptos;
}

function refreshMarket()
{
    header('Content-Type: application/json');

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $cryptoModel = new CryptoMarket();
    $cryptoModel->updateFromBinance();

    $categorie = $_GET['categorie'] ?? 'top';

    if ($categorie === 'all') {
        $cryptos = $cryptoModel->getAll();
    } else {
        $cryptos = $cryptoModel->getAllFromCat($categorie);
    }

    // Ajout de la logique watchlist si utilisateur connecté
    if (isset($_SESSION['user'])) {
        $watchlistModel = new Watchlist();
        $watchlist = $watchlistModel->getWatchlist($_SESSION['user']['id_utilisateur']);
        $watchlistIds = array_map(function($crypto) {
            return $crypto['id_crypto_market'];
        }, $watchlist);

        foreach ($cryptos as &$crypto) {
            $crypto['in_watchlist'] = in_array($crypto['id_crypto_market'], $watchlistIds);
        }
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
        $categorie = '';
        if (isset($_POST['categories']) && is_array($_POST['categories'])) {
            $categorie = implode(' ', array_map('trim', $_POST['categories']));
        }

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


function getWatchlistCrypto()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // L'utilisateur doit être connecté
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=login');
        exit();
    }
    $userId = $_SESSION['user']['id_utilisateur'];

    $watchlistModel = new Watchlist();
    $cryptos = $watchlistModel->getWatchlist($userId);
    return $cryptos;
}


?>
