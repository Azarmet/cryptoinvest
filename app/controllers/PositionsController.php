<?php
namespace App\Controllers;

use App\Models\Portefeuille;
use App\Models\Transaction;

/**
 * Renvoie la liste JSON des positions en cours (rafraîchissement toutes les secondes).
 */
function refreshPositions()
{
    header('Content-Type: application/json');
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user'])) {
        echo json_encode([]);
        exit();
    }
    $userId = $_SESSION['user']['id_utilisateur'];

    $transactionModel = new Transaction();
    $positions = $transactionModel->getOpenPositions($userId);

    echo json_encode($positions);
    exit();
}

/**
 * Ouvre une nouvelle position (long/short).
 */
function openPosition()
{
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=login');
        exit();
    }

    $userId = $_SESSION['user']['id_utilisateur'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $montant = floatval($_POST['montant'] ?? 0);
        $type = $_POST['type'] ?? 'long';  // "long" ou "short"
        $cryptoCode = !empty($_POST['crypto_code']) ? $_POST['crypto_code'] : 'BTCUSDT';

        //  Vérification du type
        if (!in_array($type, ['long', 'short'])) {
            header('Location: index.php?page=market&error=type_invalide');
            exit();
        }

        //  Vérification que la crypto est bien enregistrée
        $transactionModel = new Transaction();
        $cryptosAutorisées = $transactionModel->getCryptoTrans();
        if (!in_array($cryptoCode, $cryptosAutorisées)) {
            header('Location: index.php?page=market&error=crypto_invalide');
            exit();
        }

        //  Vérification du solde disponible
        $pfModel = new Portefeuille();
        $soldeDisponible = $pfModel->getSoldeDisponible($userId);
        if ($montant <= 0 || $montant > $soldeDisponible) {
            header('Location: index.php?page=market&error=solde_insuffisant');
            exit();
        }

        //  Ouverture de la position
        $result = $transactionModel->openPosition($userId, $montant, $type, $cryptoCode);

        if (!$result['success']) {
            header('Location: index.php?page=market&error=' . urlencode($result['error']));
            exit();
        }

        header('Location: index.php?page=market&success=position_opened');
        exit();
    }
}


/**
 * Clôture la position en cours (idTransaction).
 */
function closePosition()
{
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=login');
        exit();
    }

    $userId = $_SESSION['user']['id_utilisateur'];

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && is_numeric($_GET['id'])) {
        $idTransaction = intval($_GET['id']);
        $page = $_GET['page'] ?? 'dashboard';

        $transactionModel = new Transaction();
        $success = $transactionModel->closePosition($idTransaction, $userId);

        if ($success === false) {
            // Redirection avec message d’erreur s’il y a eu un problème
            header('Location: index.php?page=' . urlencode($page) . '&error=erreur_cloture_position');
            exit();
        }

        header('Location: index.php?page=' . urlencode($page) . '&success=position_closed');
        exit();
    } else {
        // Redirection si l'ID est manquant ou invalide
        header('Location: index.php?page=dashboard&error=id_transaction_invalide');
        exit();
    }
}
