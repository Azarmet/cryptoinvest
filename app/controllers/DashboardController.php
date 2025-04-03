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
    $cryptos = cryptoTrans();
    // Afficher la vue du dashboard
    require_once RACINE . 'app/views/dashboard.php';
}

function cryptoTrans()
{
    $transactionnModel = new Transaction();
    $cryptos = $transactionnModel->getCryptoTrans();
    return $cryptos;
}





