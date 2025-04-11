<?php
namespace App\Controllers;

use App\Models\Portefeuille;
use App\Models\User;
use App\Models\Transaction;

function showProfile()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!$_SESSION['user']['id_utilisateur']) {
        session_destroy();
        header('Location: index.php?page=login');
        exit();
    }

    $userModel = new User();
    $user = $userModel->getById($_SESSION['user']['id_utilisateur']);

    require_once RACINE . 'app/views/profil.php';
}

function showProfileByPseudo($pseudo)
{
    $userModel = new User();
    $profiluser = $userModel->getByPseudo($pseudo);
    require_once RACINE . 'app/views/profilboard.php';
}

function getStats($pseudo)
{
    if (session_status() === PHP_SESSION_NONE) session_start();

    $transactionModel = new Transaction();
    $stats = $transactionModel->getProfilboardStatsByPseudo($pseudo);

    echo json_encode($stats);
    exit;
}

function refreshPortfolioDataPseudo($pseudo)
{
    header('Content-Type: application/json');

    $userModel = new User();
    $userprofile = $userModel->getByPseudo($pseudo);
    $userId = $userprofile['id_utilisateur'];
    $interval = $_GET['interval'] ?? 'jour';

    $pfModel = new Portefeuille();
    $chartData = $pfModel->getSoldeHistory($userId, $interval);
    $stats = $pfModel->getPortfolioStats($userId);
    $currentValue = $pfModel->getSoldeActuel($userId);
    $availableBalance = $pfModel->getSoldeDisponible($userId);

    echo json_encode([
        'chartData' => $chartData,
        'stats' => $stats,
        'currentValue' => $currentValue,
        'availableBalance' => $availableBalance
    ]);
    exit();
}

function supprimerProfile(){
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $userID = $_SESSION['user']['id_utilisateur'];
    $userModel = new User();
    $userModel->deleteUser($userID);
    $_SESSION = [];
    session_destroy();
    header('Location: index.php?page=home');
    exit();
}

function logout()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION = [];
    session_destroy();
    header('Location: index.php?page=home');
    exit();
}

function updateProfile()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=login');
        exit();
    }

    $userId = $_SESSION['user']['id_utilisateur'];
    $pseudo = isset($_POST['pseudo']) ? trim(strip_tags($_POST['pseudo'])) : $_SESSION['user']['pseudo'];
    $bio = isset($_POST['bio']) ? trim(strip_tags($_POST['bio'])) : '';
    $imagePath = $_SESSION['user']['image_profil'] ?? '';
    $instagram = isset($_POST['instagram']) ? trim(filter_var($_POST['instagram'], FILTER_SANITIZE_URL)) : null;
    $x = isset($_POST['x']) ? trim(filter_var($_POST['x'], FILTER_SANITIZE_URL)) : null;
    $telegram = isset($_POST['telegram']) ? trim(strip_tags($_POST['telegram'])) : null;

    $userModel = new User();

    // Vérification si le pseudo est déjà utilisé
    if ($userModel->isPseudoTaken($pseudo, $userId)) {
        header('Location: index.php?page=profil&error=pseudo');
        exit();
    }

    // Upload image
    if (!empty($_FILES['image_profil']['name']) && $_FILES['image_profil']['error'] === 0) {
        $tmpName = $_FILES['image_profil']['tmp_name'];
        $fileSize = $_FILES['image_profil']['size'];
        $mimeType = mime_content_type($tmpName);
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 10 * 1024 * 1024;

        if ($fileSize > $maxSize) {
            header('Location: index.php?page=profil&error=size');
            exit();
        }

        if (!in_array($mimeType, $allowedTypes)) {
            header('Location: index.php?page=profil&error=type');
            exit();
        }

        $uploadsDir = RACINE . 'public/uploads/profiles/';
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0775, true);
        }

        $extension = pathinfo($_FILES['image_profil']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $targetFile = $uploadsDir . $filename;

        if (move_uploaded_file($tmpName, $targetFile)) {
            $imagePath = 'public/uploads/profiles/' . $filename;
        } else {
            header('Location: index.php?page=profil&error=upload');
            exit();
        }
    }

    $updated = $userModel->updateProfile($userId, $pseudo, $bio, $imagePath, $instagram, $x, $telegram);

    if ($updated) {
        $_SESSION['user']['pseudo'] = $pseudo;
        $_SESSION['user']['bio'] = $bio;
        $_SESSION['user']['image_profil'] = $imagePath;
        $_SESSION['user']['instagram'] = $instagram;
        $_SESSION['user']['x'] = $x;
        $_SESSION['user']['telegram'] = $telegram;

        header('Location: index.php?page=profil&success=1');
        exit();
    } else {
        header('Location: index.php?page=profil&error=1');
        exit();
    }
}
