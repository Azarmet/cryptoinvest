<?php
namespace App\Controllers;

use App\Models\Portefeuille;

function refreshPortfolioData()
{
    header('Content-Type: application/json');
    if (!isset($_SESSION['user'])) {
        echo json_encode([]);
        exit();
    }
    $userId = $_SESSION['user']['id_utilisateur'];
    $interval = $_GET['interval'] ?? 'jour';

    $pfModel = new \App\Models\Portefeuille();
    $chartData = $pfModel->getSoldeHistory($userId, $interval);
    $stats = $pfModel->getPortfolioStats($userId);
    // Valeur actuelle = capital_actuel
    $currentValue = $pfModel->getSoldeActuel($userId);
    // Solde disponible (non alloué)
    $availableBalance = $pfModel->getSoldeDisponible($userId);

    $data = [
        'chartData' => $chartData,
        'stats' => $stats,
        'currentValue' => $currentValue,
        'availableBalance' => $availableBalance
    ];
    echo json_encode($data);
    exit();
}
