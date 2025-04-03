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
 * Ouvre une nouvelle position (long/short) sur BTCUSDT.
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
        // RÉCUPÉRER LE CODE CRYPTO SÉLECTIONNÉ
        $cryptoCode = !empty($_POST['crypto_code']) ? $_POST['crypto_code'] : 'BTCUSDT';

        // Vérifier qu'on ne dépasse pas le solde disponible
        $pfModel = new Portefeuille();
        $soldeDisponible = $pfModel->getSoldeDisponible($userId);
        if ($montant <= 0 || $montant > $soldeDisponible) {
            header('Location: index.php?page=dashboard&error=solde_insuffisant');
            exit();
        }

        // Ouvrir la position en passant la crypto choisie
        $transactionModel = new Transaction();
        $transactionModel->openPosition($userId, $montant, $type, $cryptoCode);

        header('Location: index.php?page=dashboard&success=position_opened');
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

    if (isset($_GET['id'])) {
        $idTransaction = intval($_GET['id']);
        $transactionModel = new Transaction();
        $transactionModel->closePosition($idTransaction, $userId);
    }

    header('Location: index.php?page=dashboard');
    exit();
}