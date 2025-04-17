<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\Portefeuille;

/**
 * Affiche la page d’accueil publique avec le classement des utilisateurs.
 *
 * - Récupère le leaderboard via GetExtractLeaderboard().
 * - Charge la vue frontoffice/app/views/home.php.
 */

function showHome()
{   
    $usersWithSolde = GetExtractLeaderboard();
    require_once RACINE . 'app/views/home.php';
}

/**
 * Affiche la page d’accueil du back-office.
 */
function showBackHome()
{
    require_once RACINE . 'app/views/backoffice/home.php';
}

/**
 * Construit le leaderboard des utilisateurs basé sur leur solde actuel.
 *
 * - Récupère tous les IDs utilisateurs.
 * - Pour chaque utilisateur :  
 *     • Lit le profil (pseudo, image).  
 *     • Récupère le solde actuel via Portefeuille::getSoldeActuel().  
 * - Trie les utilisateurs par solde décroissant.  
 * - Renvoie les 3 premiers (top 3).
 *
 * @return array Tableau des meilleurs utilisateurs avec clés :
 *               'id', 'pseudo', 'image', 'solde'.
 */
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
    $usersWithSolde = array_slice($usersWithSolde, 0, 3);
    return $usersWithSolde;
}
?>