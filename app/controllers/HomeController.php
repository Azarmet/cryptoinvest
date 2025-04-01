<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\Portefeuille;

function showHome()
{   
    $usersWithSolde = GetExtractLeaderboard();
    require_once RACINE . 'app/views/home.php';
}

function showBackHome()
{
    require_once RACINE . 'app/views/backoffice/home.php';
}

function GetExtractLeaderboard()
{
    $userModel = new User();
    $pfUser = new Portefeuille();

    $ListUserID = $userModel->getAllIdUsers();
    $usersWithSolde = [];

    // 1. Récupérer tous les utilisateurs avec leur solde
    foreach ($ListUserID as $user) {
        $userID = $user['id_utilisateur'];
        $profil = $userModel->getById($userID);
        $solde = $pfUser->getSoldeActuel($userID);

        $usersWithSolde[] = [
            'id' => $userID,
            'pseudo' => $profil['pseudo'],
            'image' => $profil['image_profil'],
            'solde' => $solde
        ];
    }

    // 2. Trier par solde décroissant
    usort($usersWithSolde, function ($a, $b) {
        return $b['solde'] <=> $a['solde'];
    });

    // 3. Limiter aux 10 premiers résultats
    $usersWithSolde = array_slice($usersWithSolde, 0, 10);
    return $usersWithSolde;
}
?>