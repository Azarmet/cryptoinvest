<?php
namespace App\Controllers;

use App\Models\CryptoMarket;
use App\Models\Watchlist;

function showWatchlist()
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

    require_once RACINE . 'app/views/watchlist.php';
}

function addToWatchlist()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=login');
        exit();
    }
    $userId = $_SESSION['user']['id_utilisateur'];
    if (isset($_GET['id'])) {
        $cryptoId = intval($_GET['id']);
        $watchlistModel = new Watchlist();
        $watchlistModel->add($userId, $cryptoId);
    }
    // Rediriger vers la page Market (l'ajout se fait généralement depuis le Market)
    header('Location: index.php?page=market');
    exit();
}

function removeFromWatchlist()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=login');
        exit();
    }
    $userId = $_SESSION['user']['id_utilisateur'];
    if (isset($_GET['id'])) {
        $cryptoId = intval($_GET['id']);
        $watchlistModel = new Watchlist();
        $watchlistModel->remove($userId, $cryptoId);
    }
    // Rediriger vers la page Watchlist
    header('Location: index.php?page=watchlist');
    exit();
}

function refreshWatchlist()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user'])) {
        header('HTTP/1.1 403 Forbidden');
        exit();
    }

    // 1) Mettre à jour la table cryptomarket depuis l'API Binance
    $cryptoModel = new CryptoMarket();
    $cryptoModel->updateFromBinance();

    // 2) Récupérer la watchlist de l'utilisateur
    $watchlistModel = new Watchlist();
    $userId = $_SESSION['user']['id_utilisateur'];
    $cryptos = $watchlistModel->getWatchlist($userId);

    // 3) Envoyer la réponse en JSON
    header('Content-Type: application/json');
    echo json_encode($cryptos);
    exit();
}
?>
