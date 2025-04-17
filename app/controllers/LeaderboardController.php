<?php

namespace App\Controllers;

use App\Models\Portefeuille;
use App\Models\Transaction;
use App\Models\User;


/**
 * Affiche le leaderboard général des utilisateurs.
 *
 * - Récupère tous les IDs utilisateurs.
 * - Pour chaque utilisateur :
 *     • Récupère le profil (pseudo, image).
 *     • Récupère le solde actuel.
 *     • Calcule le PnL sur 24h et 7 jours.
 * - Trie les utilisateurs par solde décroissant.
 * - Charge la vue leaderboard.php.
 */
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
        $pnl24h = $pfUser->getPnL($userID, 1);
        $pnl7j = $pfUser->getPnL($userID, 7);

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


/**
 * Point d’entrée pour la recherche d’utilisateurs via AJAX.
 *
 * - Vérifie que l’action demandée est 'search'.
 * - Lit le terme de recherche en GET.
 * - Parcourt tous les utilisateurs et filtre par pseudo.
 * - Calcule pour chaque résultat :
 *     • solde actuel, PnL 24h et PnL 7j.
 * - Trie les résultats par solde décroissant.
 * - Formate les valeurs numériques et ajoute le rang.
 * - Retourne le JSON des résultats.
 */
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
                $pnl24h = $pfModel->getPnL($userID, 1);
                $pnl7j = $pfModel->getPnL($userID, 7);

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