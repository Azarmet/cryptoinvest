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
    if(!$_SESSION['user']['id_utilisateur']):
        // Détruire la session
    session_destroy();
    // Rediriger vers la page d'accueil (ou la page de connexion)
    header('Location: index.php?page=login');
    exit();
    endif;
    $userModel = new User();
    $user = $userModel->getById($_SESSION['user']['id_utilisateur']);
    // Afficher la vue du profil
    require_once RACINE . 'app/views/profil.php';
}

function showProfileByPseudo($pseudo)
{
    $userModel = new User();
    $profiluser = $userModel->getByPseudo($pseudo);
    require_once RACINE . 'app/views/profilboard.php';
}


function getStats($pseudo) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $transactionModel = new \App\Models\Transaction();
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

function logout()
{
    // Démarrer la session si elle n'est pas déjà lancée
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // Vider le tableau de session
    $_SESSION = [];
    // Détruire la session
    session_destroy();
    // Rediriger vers la page d'accueil (ou la page de connexion)
    header('Location: index.php?page=home');
    exit();
}

function updateProfile()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Vérification de connexion
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=login');
        exit();
    }

    $userId = $_SESSION['user']['id_utilisateur'];
    $bio = isset($_POST['bio']) ? trim(strip_tags($_POST['bio'])) : '';
    $imagePath = $_SESSION['user']['image_profil'] ?? '';

    // Champs réseaux sociaux (nettoyés)
    $instagram = isset($_POST['instagram']) ? trim(filter_var($_POST['instagram'], FILTER_SANITIZE_URL)) : null;
    $x = isset($_POST['x']) ? trim(filter_var($_POST['x'], FILTER_SANITIZE_URL)) : null;
    $telegram = isset($_POST['telegram']) ? trim(strip_tags($_POST['telegram'])) : null;

    // Upload de l'image de profil
    if (!empty($_FILES['image_profil']['name']) && $_FILES['image_profil']['error'] === 0) {
        $tmpName = $_FILES['image_profil']['tmp_name'];
        $fileSize = $_FILES['image_profil']['size'];
        $mimeType = mime_content_type($tmpName);
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 10 * 1024 * 1024;  // 10 Mo

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

    // Mise à jour du profil dans la base
    $userModel = new User();
    $updated = $userModel->updateProfile($userId, $bio, $imagePath, $instagram, $x, $telegram);

    if ($updated) {
        // MAJ session
        $_SESSION['user']['bio'] = $bio;
        $_SESSION['user']['image_profil'] = $imagePath;
        $_SESSION['user']['instagram'] = $instagram;
        $_SESSION['user']['x'] = $x;
        $_SESSION['user']['telegram'] = $telegram;

        header('Location: index.php?page=profil');
        exit();
    } else {
        header('Location: index.php?page=profil&error=1');
        exit();
    }
}


?>
