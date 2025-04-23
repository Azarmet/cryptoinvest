<?php
namespace App\Controllers;

use App\Models\User;

/**
 * Affiche la page de connexion.
 */
function showLogin()
{
    require_once RACINE . 'app/views/login.php';
}


/**
 * Traite la soumission du formulaire de connexion.
 *
 * - Valide et assainit l'email et le mot de passe.
 * - Authentifie l'utilisateur via User::login().
 * - Démarre la session et redirige en cas de succès.
 * - Réaffiche le formulaire avec message d'erreur en cas d'échec.
 *
 */
function processLogin()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Valider et assainir l'email
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = trim($_POST['password']);
        
        if (!$email || empty($password)) {
            $error = 'Veuillez fournir un email valide et un mot de passe.';
            require_once RACINE . 'app/views/login.php';
            exit();
        }

        $userModel = new User();
        $user = $userModel->login($email, $password);
        if ($user) {
            session_start();
            // Régénérer l'ID de session pour prévenir la fixation de session
            session_regenerate_id(true);
            $_SESSION['user'] = $user;
            $_SESSION['role'] = $user['role'];

            if(isset($_SESSION['role']) &&  $_SESSION['role'] === 'admin'){
                $_SESSION['uniqueID'] =  session_id();
            }
            header('Location: index.php?page=home');
            exit();
        } else {
            $error = 'Email ou mot de passe incorrect.';
            require_once RACINE . 'app/views/login.php';
        }
    }
}


/**
 * Traite la soumission du formulaire d'inscription.
 *
 * - Valide et assainit les champs (email, pseudo, mots de passe).
 * - Vérifie la correspondance des mots de passe.
 * - Appelle User::register() pour créer l'utilisateur et son portefeuille.
 * - Redirige ou réaffiche le formulaire avec message d'erreur.
 */

function processRegister()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Assainir et valider les entrées
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $pseudo = trim(strip_tags($_POST['pseudo'] ?? ''));
        $password = trim($_POST['password']);
        $confirmPassword = trim($_POST['confirm_password']);

        if (!$email) {
            $error = 'Email invalide.';
            require_once RACINE . 'app/views/register.php';
            exit();
        }

        if (empty($pseudo)) {
            $error = 'Le pseudo ne peut pas être vide.';
            require_once RACINE . 'app/views/register.php';
            exit();
        }

        if (empty($password) || empty($confirmPassword)) {
            $error = 'Les mots de passe sont obligatoires.';
            require_once RACINE . 'app/views/register.php';
            exit();
        }

        if ($password !== $confirmPassword) {
            $error = 'Les mots de passe ne correspondent pas.';
            require_once RACINE . 'app/views/register.php';
            exit();
        }


        $userModel = new User();
        $result = $userModel->register($email, $pseudo, $password);
        if (!$result['success']) {
            $error = $result['error'];
            // afficher le message dans la vue
            require_once RACINE . 'app/views/register.php';
        }


        if ($result['success']) {
            processLogin();
            exit();
        } else {
            $error = "Erreur lors de l'inscription. Veuillez réessayer.";
            require_once RACINE . 'app/views/register.php';
        }
    }
}
?>
