<?php
namespace App\Controllers;

use App\Models\CryptoMarket;
use App\Models\CryptoTrans;
use App\Models\Watchlist;
use App\Models\Transaction;


/**
 * Affiche la page « Market » publique.
 *
 * - Met à jour les données depuis Binance (optionnel).
 * - Récupère les cryptos selon la catégorie.
 * - Récupère la liste des cryptos dans Transactions.
 * - Ajoute l’état « in_watchlist » si l’utilisateur est connecté.
 * - Charge la vue app/views/market.php.
 */
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

/**
 * Récupère tous les codes de cryptos disponibles pour les transactions.
 *
 * @return string[] Liste de codes (ex. « BTCUSDT »)
 */
function cryptoTrans()
{
    $transactionnModel = new Transaction();
    $cryptos = $transactionnModel->getCryptoTrans();
    return $cryptos;
}


/**
 * Rafraîchit les données de marché en AJAX.
 *
 * - Met à jour depuis Binance.
 * - Récupère les cryptos (toutes ou par catégorie).
 * - Ajoute l’état watchlist si connecté.
 * - Retourne le JSON des cryptos.
 */
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

/**
 * Affiche la page « Market » du back-office.
 *
 * - Récupère les cryptos du marché et les cryptos enregistrées pour transactions.
 * - Charge la vue backoffice/app/views/backoffice/market.php.
 */

function showBackMarket()
{
    $marketModel = new CryptoMarket();
    $transModel = new CryptoTrans();

    $marketCryptos = $marketModel->getAll();
    $transCryptos = $transModel->getAll();

    require_once RACINE . 'app/views/backoffice/market.php';
}

/**
 * Crée une nouvelle crypto dans le back-office (cryptomarket).
 *
 * - Valide les champs POST (code, catégories).
 * - Appelle CryptoMarket::createCrypto().
 * - Redirige avec indicateur de succès ou affiche l’erreur.
 */
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

/**
 * Supprime une crypto du back-office (cryptomarket).
 *
 * @param int $id Identifiant de la crypto à supprimer.
 */
function deleteCryptoMarket($id)
{
    $model = new CryptoMarket();
    $model->deleteCrypto($id);
    header('Location: index.php?pageback=market&success=2');
    exit;
}

/**
 * Crée une nouvelle crypto pour transactions (cryptotrans) dans le back-office.
 *
 * - Valide le code POST.
 * - Appelle CryptoTrans::createCrypto().
 */
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

/**
 * Supprime une crypto pour transactions (cryptotrans) dans le back-office.
 *
 * @param int $id Identifiant de la crypto à supprimer.
 */
function deleteCryptoTrans($id)
{
    $model = new CryptoTrans();
    $model->deleteCrypto($id);
    header('Location: index.php?pageback=market&success=4');
    exit;
}


/**
 * Récupère la watchlist d’un utilisateur connecté.
 *
 * - Vérifie la session.
 * - Appelle Watchlist::getWatchlist().
 *
 * @return array Tableau associatif des cryptos en watchlist.
 */
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
