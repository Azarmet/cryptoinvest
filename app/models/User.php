<?php
namespace App\Models;

use PDO;
use App\Models\Database;

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // Inscription : ajoute un nouvel utilisateur
    public function register($email, $pseudo, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO utilisateur (email, pseudo, mot_de_passe) VALUES (:email, :pseudo, :mot_de_passe)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':email'       => $email,
            ':pseudo'      => $pseudo,
            ':mot_de_passe'=> $hash
        ]);
    }

    // Login : vérifie l'identifiant et retourne l'utilisateur si correct
    public function login($email, $password) {
        $sql = "SELECT * FROM utilisateur WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            return $user;
        }
        return false;
    }
}
?>
