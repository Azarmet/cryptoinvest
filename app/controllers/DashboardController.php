<?php
namespace App\Controllers;

use App\Models\Portefeuille;
use App\Models\Transaction;

function showDashboard()
{
    // Vérifier la session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=login');
        exit();
    }
    $transactions = getHistoryTransaction();
    // Afficher la vue du dashboard
    require_once RACINE . 'app/views/dashboard.php';
}

function getStats() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['user'])) {
        echo json_encode(['error' => 'Non connecté']);
        exit;
    }

    $userId = $_SESSION['user']['id_utilisateur'];
    $transactionModel = new \App\Models\Transaction();
    $stats = $transactionModel->getDashboardStats($userId);

    echo json_encode($stats);
    exit;
}

function getHistoryTransaction(){
// Vérifier la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=login');
    exit();
}
$userId = $_SESSION['user']['id_utilisateur'];

$transactionModel = new Transaction();
$transactions = $transactionModel->getTransactionsByUserId($userId);
return $transactions;
}
