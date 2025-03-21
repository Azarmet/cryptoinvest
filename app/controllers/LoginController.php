<?php
namespace App\Controllers;

use App\Models\User;

function showLogin() {
    require_once RACINE . "app/views/login.php";
}

function processLogin() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Valider et assainir l'email
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = trim($_POST['password']);

        if (!$email || empty($password)) {
            $error = "Veuillez fournir un email valide et un mot de passe.";
            require_once RACINE . "app/views/login.php";
            exit();
        }
        
        $userModel = new User();
        $user = $userModel->login($email, $password);
        
        if ($user) {
            session_start();
            // Régénérer l'ID de session pour prévenir la fixation de session
            session_regenerate_id(true);
            $_SESSION['user'] = $user;
            header('Location: index.php?page=home');
            exit();
        } else {
            $error = "Email ou mot de passe incorrect.";
            require_once RACINE . "app/views/login.php";
        }
    }
}

function processRegister() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Assainir et valider les entrées
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $pseudo = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_STRING);
        $password = trim($_POST['password']);
        $confirmPassword = trim($_POST['confirm_password']);

        if (!$email) {
            $error = "Email invalide.";
            require_once RACINE . "app/views/register.php";
            exit();
        }
        
        if (empty($pseudo)) {
            $error = "Le pseudo ne peut pas être vide.";
            require_once RACINE . "app/views/register.php";
            exit();
        }
        
        if (empty($password) || empty($confirmPassword)) {
            $error = "Les mots de passe sont obligatoires.";
            require_once RACINE . "app/views/register.php";
            exit();
        }

        if ($password !== $confirmPassword) {
            $error = "Les mots de passe ne correspondent pas.";
            require_once RACINE . "app/views/register.php";
            exit();
        }
        
        // Vous pouvez ajouter ici un contrôle de la robustesse du mot de passe (longueur, complexité, etc.)

        $userModel = new User();
        $result = $userModel->register($email, $pseudo, $password);
        
        if ($result) {
            header('Location: index.php?page=login');
            exit();
        } else {
            $error = "Erreur lors de l'inscription. Veuillez réessayer.";
            require_once RACINE . "app/views/register.php";
        }
    }
}
?>
