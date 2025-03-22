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
        if (!$stmt->execute([
            ':email'       => $email,
            ':pseudo'      => $pseudo,
            ':mot_de_passe'=> $hash
        ])) {
            return false;
        }
    
        $newUserId = $this->pdo->lastInsertId();
    
        // Créer automatiquement le portefeuille pour ce user
        $sqlPortefeuille = "INSERT INTO portefeuille (capital_initial, id_utilisateur) VALUES (10000, :userId)";
        $stmtPf = $this->pdo->prepare($sqlPortefeuille);
        if (!$stmtPf->execute([':userId' => $newUserId])) {
            return false;
        }
    
        return true;
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

    // Méthode pour mettre à jour la bio et l'image de profil de l'utilisateur
    public function updateProfile($userId, $bio, $imagePath) {
        $sql = "UPDATE utilisateur SET bio = :bio, image_profil = :image_profil WHERE id_utilisateur = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':bio' => $bio,
            ':image_profil' => $imagePath,
            ':id' => $userId
        ]);
    }

    // (Optionnel) Méthode pour récupérer l'utilisateur par son ID
    public function getById($userId) {
        $sql = "SELECT * FROM utilisateur WHERE id_utilisateur = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $userId]);
        return $stmt->fetch();
    }
}
?>
