<?php
namespace App\Controllers;

use App\Models\Transaction;
use App\Models\Portefeuille;

function showDashboard() {
    // Vérifier la session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user'])) {
        header("Location: index.php?page=login");
        exit();
    }

    // Afficher la vue du dashboard
    require_once RACINE . "app/views/dashboard.php";
}

/**
 * Ouvre une nouvelle position (long/short) sur BTCUSDT.
 */
function openPosition() {
    session_start();
    if (!isset($_SESSION['user'])) {
        header("Location: index.php?page=login");
        exit();
    }
    $userId = $_SESSION['user']['id_utilisateur'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $montant = floatval($_POST['montant'] ?? 0);
        $type    = $_POST['type'] ?? 'long'; // "long" ou "short"

        // Vérifier qu'on ne dépasse pas le solde disponible
        $pfModel = new Portefeuille();
        $soldeDisponible = $pfModel->getSoldeDisponible($userId);
        if ($montant <= 0 || $montant > $soldeDisponible) {
            // Rediriger avec un message d'erreur
            header("Location: index.php?page=dashboard&error=solde_insuffisant");
            exit();
        }

        // Créer la transaction via le modèle
        $transactionModel = new Transaction();
        $transactionModel->openPosition($userId, $montant, $type);

        header("Location: index.php?page=dashboard&success=position_opened");
        exit();
    }
}

/**
 * Clôture la position en cours (idTransaction).
 */
function closePosition() {
    session_start();
    if (!isset($_SESSION['user'])) {
        header("Location: index.php?page=login");
        exit();
    }
    $userId = $_SESSION['user']['id_utilisateur'];

    if (isset($_GET['id'])) {
        $idTransaction = intval($_GET['id']);
        $transactionModel = new Transaction();
        $transactionModel->closePosition($idTransaction, $userId);
    }

    header("Location: index.php?page=dashboard");
    exit();
}

/**
 * Renvoie la liste JSON des positions en cours (rafraîchissement toutes les secondes).
 */
function refreshPositions() {
    header('Content-Type: application/json');
    session_start();
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
 * Renvoie en JSON :
 *  - chartData : l'historique du solde pour tracer le graphique
 *  - stats : {roiTotal, pnlTotal, txCount}
 */
function refreshPortfolioData() {
    header('Content-Type: application/json');
    session_start();
    if (!isset($_SESSION['user'])) {
        echo json_encode([]);
        exit();
    }
    $userId = $_SESSION['user']['id_utilisateur'];
    $interval = $_GET['interval'] ?? 'jour';

    $pfModel = new \App\Models\Portefeuille();
    $chartData = $pfModel->getSoldeHistory($userId, $interval);
    $stats     = $pfModel->getPortfolioStats($userId);
    // Valeur actuelle = capital_actuel
    $currentValue = $pfModel->getSoldeActuel($userId);
    // Solde disponible (non alloué)
    $availableBalance = $pfModel->getSoldeDisponible($userId);

    $data = [
        'chartData'       => $chartData,
        'stats'           => $stats,
        'currentValue'    => $currentValue,
        'availableBalance'=> $availableBalance
    ];
    echo json_encode($data);
    exit();
}



