<?php
namespace App\Controllers;

use App\Models\User;

function showLogin() {
    require_once RACINE . "app/views/login.php";
}

function processLogin() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email    = trim($_POST['email']);
        $password = $_POST['password'];

        $userModel = new User();
        $user = $userModel->login($email, $password);
        
        if ($user) {
            session_start();
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
        $email           = trim($_POST['email']);
        $pseudo          = trim($_POST['pseudo']);
        $password        = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($password !== $confirmPassword) {
            $error = "Les mots de passe ne correspondent pas.";
            require_once RACINE . "app/views/register.php";
            exit();
        }
        
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
