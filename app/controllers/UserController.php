<?php
namespace App\Controllers;

use App\Models\User;

function showBackUsers() {
    $userModel = new User();
    $users = $userModel->getAllUsers();

    require_once RACINE . "app/views/backoffice/users.php";
}

function deleteUser($id) {

    // Empêcher de supprimer l'utilisateur connecté
    if ($_SESSION['user']['id_utilisateur'] == $id) {
        header("Location: index.php?pageback=users&error=cannot_delete_self");
        exit;
    }

    $userModel = new User();
    $userModel->deleteUser($id);

    header("Location: index.php?pageback=users&success=1");
    exit;
}

function toggleUserRole($id) {
    $userModel = new \App\Models\User();
    $users = $userModel->getAllUsers();

    // Trouver l'utilisateur en question
    foreach ($users as $u) {
        if ($u['id_utilisateur'] == $id) {
            $newRole = ($u['role'] === 'admin') ? 'utilisateur' : 'admin';

            // Interdire de se modifier soi-même
            if ($_SESSION['user']['id_utilisateur'] == $id) {
                header("Location: index.php?pageback=users&error=modify_self");
                exit;
            }

            $userModel->updateRole($id, $newRole);
            header("Location: index.php?pageback=users&success=2");
            exit;
        }
    }

    header("Location: index.php?pageback=users&error=user_not_found");
}

function searchUser() {
    header('Content-Type: application/json');
    $term = isset($_GET['q']) ? trim($_GET['q']) : '';
    $model = new \App\Models\User();
    $results = $model->searchUsers($term);
    echo json_encode($results);
    exit;
}

