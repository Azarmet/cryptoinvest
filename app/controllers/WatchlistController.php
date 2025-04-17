<?php
namespace App\Controllers;

use App\Models\CryptoMarket;
use App\Models\Watchlist;

/**
 * Affiche la page de la watchlist d’un utilisateur connecté.
 *
 * - Vérifie la session et l’authentification.
 * - Récupère la liste des cryptos en watchlist via Watchlist::getWatchlist().
 * - Charge la vue app/views/watchlist.php.
 */
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

/**
 * Ajoute une cryptomonnaie à la watchlist de l’utilisateur connecté.
 *
 * - Vérifie la session et l’authentification.
 * - Lit l’ID de la crypto en GET.
 * - Appelle Watchlist::add().
 * - Redirige vers la page Market.
 */
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

/**
 * Supprime une cryptomonnaie de la watchlist de l’utilisateur connecté.
 *
 * - Vérifie la session et l’authentification.
 * - Lit l’ID de la crypto en GET.
 * - Appelle Watchlist::remove().
 * - Redirige vers la page Watchlist.
 */
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

/**
 * Rafraîchit la watchlist de l’utilisateur en AJAX (JSON).
 *
 * - Vérifie la session et l’authentification.
 * - Met à jour les prix via CryptoMarket::updateFromBinance().
 * - Récupère la watchlist via Watchlist::getWatchlist().
 * - Retourne un JSON de la liste mise à jour.
 */
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
