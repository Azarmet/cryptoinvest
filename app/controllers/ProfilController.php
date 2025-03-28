<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\Portefeuille;

function showProfile() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // Afficher la vue du profil
    require_once RACINE . "app/views/profil.php";
}

function showProfileByPseudo($pseudo){
    $userModel = new User();
    $profiluser = $userModel->getByPseudo($pseudo);
    require_once RACINE . "app/views/profilboard.php";
}

function refreshPortfolioDataPseudo($pseudo) {
    header('Content-Type: application/json');
  
    $userModel = new User();
    $userprofile =  $userModel -> getByPseudo($pseudo);
    $userId = $userprofile['id_utilisateur'];
    $interval = $_GET['interval'] ?? 'jour';

    $pfModel = new \App\Models\Portefeuille();
    $chartData = $pfModel->getSoldeHistory($userId, $interval);
    $stats     = $pfModel->getPortfolioStats($userId);
    // Valeur actuelle = capital_actuel
    $currentValue = $pfModel->getSoldeActuel($userId);
    // Solde disponible (non alloué)
    $availableBalance = $pfModel->getSoldeDisponible($userId);

    $data = [
        'chartData'       => $chartData,
        'stats'           => $stats,
        'currentValue'    => $currentValue,
        'availableBalance'=> $availableBalance
    ];
    echo json_encode($data);
    exit();
}





function logout() {
    // Démarrer la session si elle n'est pas déjà lancée
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // Vider le tableau de session
    $_SESSION = [];
    // Détruire la session
    session_destroy();
    // Rediriger vers la page d'accueil (ou la page de connexion)
    header("Location: index.php?page=home");
    exit();
}


function updateProfile() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // Vérifier que l'utilisateur est connecté
    if (!isset($_SESSION['user'])) {
        header("Location: index.php?page=login");
        exit();
    }
    $userId = $_SESSION['user']['id_utilisateur'];

    // Récupérer la bio
    $bio = isset($_POST['bio']) ? trim($_POST['bio']) : '';
    $imagePath = isset($_SESSION['user']['image_profil']) ? $_SESSION['user']['image_profil'] : '';

    // Gestion de l'upload de l'image de profil
    if (isset($_FILES['image_profil']) && $_FILES['image_profil']['error'] == UPLOAD_ERR_OK) {
        // Liste des types autorisés (jpeg, png, gif)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['image_profil']['type'], $allowedTypes)) {
            // Définir le dossier de destination (par exemple public/uploads/profiles/)
            $uploadsDir = RACINE . "public/uploads/profiles/";
            if (!is_dir($uploadsDir)) {
                mkdir($uploadsDir, 0777, true);
            }
            // Créer un nom de fichier unique
            $filename = uniqid() . "_" . basename($_FILES['image_profil']['name']);
            $targetFile = $uploadsDir . $filename;
            if (move_uploaded_file($_FILES['image_profil']['tmp_name'], $targetFile)) {
                // Enregistrer le chemin relatif (pour être accessible via le navigateur)
                $imagePath = "public/uploads/profiles/" . $filename;
            }
        }
    }

    // Mettre à jour le profil dans la base
    $userModel = new User();
    $updated = $userModel->updateProfile($userId, $bio, $imagePath);

    if ($updated) {
        // Actualiser les données de session
        $_SESSION['user']['bio'] = $bio;
        $_SESSION['user']['image_profil'] = $imagePath;
        header("Location: index.php?page=profil&success=1");
        exit();
    } else {
        header("Location: index.php?page=profil&error=1");
        exit();
    }
}
?>
