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

?>