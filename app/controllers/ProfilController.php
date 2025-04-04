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

    // Vérifier que l'utilisateur est connecté
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=login');
        exit();
    }

    $userId = $_SESSION['user']['id_utilisateur'];
    $bio = isset($_POST['bio']) ? trim($_POST['bio']) : '';
    $imagePath = $_SESSION['user']['image_profil'] ?? '';

    // Gestion de l'upload de l'image de profil
    if (!empty($_FILES['image_profil']['name']) && $_FILES['image_profil']['error'] === 0) {
        $tmpName = $_FILES['image_profil']['tmp_name'];
        $fileSize = $_FILES['image_profil']['size'];
        $mimeType = mime_content_type($tmpName);
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 10 * 1024 * 1024;  // 10 Mo

        // Vérification taille
        if ($fileSize > $maxSize) {
            header('Location: index.php?page=profil&error=size');
            exit();
        }

        // Vérification type MIME
        if (!in_array($mimeType, $allowedTypes)) {
            header('Location: index.php?page=profil&error=type');
            exit();
        }

        // Dossier de destination
        $uploadsDir = RACINE . 'public/uploads/profiles/';
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0775, true);
        }

        // Nom unique + extension
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
    $updated = $userModel->updateProfile($userId, $bio, $imagePath);

    if ($updated) {
        $_SESSION['user']['bio'] = $bio;
        $_SESSION['user']['image_profil'] = $imagePath;
        header('Location: index.php?page=profil&success=1');
        exit();
    } else {
        header('Location: index.php?page=profil&error=1');
        exit();
    }
}

?>
