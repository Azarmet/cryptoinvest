<?php
namespace App\Controllers;

use App\Models\User;

/**
 * Contrôleur du back-office pour la gestion des utilisateurs.
 */

/**
 * Affiche la liste de tous les utilisateurs dans le back-office.
 *
 * - Récupère tous les utilisateurs via User::getAllUsers().
 * - Charge la vue app/views/backoffice/users.php.
 */
function showBackUsers()
{
    $userModel = new User();
    $users = $userModel->getAllUsers();

    require_once RACINE . 'app/views/backoffice/users.php';
}

/**
 * Supprime un utilisateur donné par son identifiant.
 *
 * - Empêche la suppression de son propre compte.
 * - Appelle User::deleteUser().
 * - Redirige avec un code de succès ou d’erreur.
 *
 * @param int $id Identifiant de l'utilisateur à supprimer.
 */

function deleteUser($id)
{
    // Empêcher de supprimer l'utilisateur connecté
    if ($_SESSION['user']['id_utilisateur'] == $id) {
        header('Location: index.php?pageback=users&error=cannot_delete_self');
        exit;
    }

    $userModel = new User();
    $userModel->deleteUser($id);

    header('Location: index.php?pageback=users&success=1');
    exit;
}

/**
 * Bascule le rôle d'un utilisateur entre 'admin' et 'utilisateur'.
 *
 * - Charge tous les utilisateurs et recherche celui dont l'ID correspond.
 * - Empêche de modifier son propre rôle.
 * - Appelle User::updateRole().
 *
 * @param int $id Identifiant de l'utilisateur dont le rôle doit être modifié.
 */
function toggleUserRole($id)
{
    $userModel = new \App\Models\User();
    $users = $userModel->getAllUsers();

    // Trouver l'utilisateur en question
    foreach ($users as $u) {
        if ($u['id_utilisateur'] == $id) {
            $newRole = ($u['role'] === 'admin') ? 'utilisateur' : 'admin';

            // Interdire de se modifier soi-même
            if ($_SESSION['user']['id_utilisateur'] == $id) {
                header('Location: index.php?pageback=users&error=modify_self');
                exit;
            }

            $userModel->updateRole($id, $newRole);
            header('Location: index.php?pageback=users&success=2');
            exit;
        }
    }

    header('Location: index.php?pageback=users&error=user_not_found');
}

/**
 * Point d'entrée AJAX pour la recherche d'utilisateurs.
 *
 * - Lit le terme de recherche dans $_GET['q'].
 * - Appelle User::searchUsers().
 * - Retourne les résultats au format JSON.
 */
function searchUser()
{
    header('Content-Type: application/json');
    $term = isset($_GET['q']) ? trim($_GET['q']) : '';
    $model = new \App\Models\User();
    $results = $model->searchUsers($term);
    echo json_encode($results);
    exit;
}
