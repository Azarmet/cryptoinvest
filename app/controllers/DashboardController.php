<?php
namespace App\Controllers;

use App\Models\Portefeuille;
use App\Models\Transaction;

/**
 * Affiche le tableau de bord utilisateur.
 *
 * - Vérifie l’authentification via la session.
 * - Récupère l’historique des transactions.
 * - Charge la vue dashboard.php.
 */
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

/**
 * Renvoie les statistiques du dashboard au format JSON.
 *
 * - Vérifie l’authentification.
 * - Appelle Transaction::getDashboardStats().
 */

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

/**
 * Récupère l’historique complet des transactions de l’utilisateur.
 *
 * - Vérifie l’authentification.
 * - Utilise Transaction::getTransactionsByUserId().
 *
 * @return array Tableau associatif des transactions.
 */
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
