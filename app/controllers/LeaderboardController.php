<?php

namespace App\Controllers;

use App\Models\Portefeuille;
use App\Models\Transaction;
use App\Models\User;

function showLeaderboard()
{
    $userModel = new User();
    $pfUser = new Portefeuille();

    $ListUserID = $userModel->getAllIdUsers();
    $usersWithSolde = [];

    foreach ($ListUserID as $user) {
        $userID = $user['id_utilisateur'];
        $profil = $userModel->getById($userID);
        $solde = $pfUser->getSoldeActuel($userID);
        $pnl24h = $pfUser->getPnL24h($userID);
        $pnl7j = $pfUser->getPnL7j($userID);

        $usersWithSolde[] = [
            'id' => $userID,
            'pseudo' => $profil['pseudo'],
            'image' => $profil['image_profil'],
            'solde' => $solde,
            'pnl_24h' => $pnl24h,
            'pnl_7j' => $pnl7j
        ];
    }

    usort($usersWithSolde, function ($a, $b) {
        return $b['solde'] <=> $a['solde'];
    });

    require_once RACINE . 'app/views/leaderboard.php';
}

function search_user() {
    if (isset($_GET['action']) && $_GET['action'] === 'search') {
        header('Content-Type: application/json');

        $query = $_GET['term'] ?? '';
        $userModel = new User();
        $pfModel = new Portefeuille();

        $allUsers = $userModel->getAllIdUsers();
        $result = [];

        foreach ($allUsers as $user) {
            $userID = $user['id_utilisateur'];
            $profil = $userModel->getById($userID);

            if (stripos($profil['pseudo'], $query) !== false) {
                $solde = $pfModel->getSoldeActuel($userID);
                $pnl24h = $pfModel->getPnL24h($userID);
                $pnl7j = $pfModel->getPnL7j($userID);

                $result[] = [
                    'id' => $userID,
                    'pseudo' => $profil['pseudo'],
                    'image' => $profil['image_profil'],
                    'solde_val' => $solde,
                    'pnl24h_val' => $pnl24h,
                    'pnl7j_val' => $pnl7j
                ];
            }
        }

        // Trier par solde décroissant
        usort($result, fn($a, $b) => $b['solde_val'] <=> $a['solde_val']);

        // Réattribuer les ranks + formater les nombres pour affichage
        foreach ($result as $index => &$row) {
            $row['rank'] = $index + 1;
            $row['solde'] = number_format($row['solde_val'], 2, ',', ' ');
            $row['pnl_24h'] = number_format($row['pnl24h_val'], 2, ',', ' ');
            $row['pnl_7j'] = number_format($row['pnl7j_val'], 2, ',', ' ');

            // Supprimer les valeurs brutes après usage
            unset($row['solde_val'], $row['pnl24h_val'], $row['pnl7j_val']);
        }

        echo json_encode($result);
        exit;
    }
}


?>