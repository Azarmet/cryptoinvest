<?php
namespace App\Models;

use App\Models\Database;
use PDO;

class User
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // Inscription : ajoute un nouvel utilisateur
    public function register($email, $pseudo, $password)
    {
        // === VALIDATION ===

        // Email valide
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'Adresse e-mail invalide.'];
        }

        // Pseudo : 3-10 caractères, lettres/chiffres/underscores
        if (!preg_match('/^[a-zA-Z0-9_]{3,10}$/', $pseudo)) {
            return ['success' => false, 'error' => 'Le pseudo doit contenir entre 3 et 10 caractères alphanumériques ou underscores.'];
        }

        // Mot de passe : minimum 8 caractères
        if (strlen($password) < 8) {
            return ['success' => false, 'error' => 'Le mot de passe doit contenir au moins 8 caractères.'];
        }

        // === UNICITÉ EMAIL ===
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM utilisateur WHERE email = :email');
        $stmt->execute([':email' => $email]);
        if ($stmt->fetchColumn() > 0) {
            return ['success' => false, 'error' => 'Cet email est déjà utilisé.'];
        }

        // === UNICITÉ PSEUDO ===
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM utilisateur WHERE pseudo = :pseudo');
        $stmt->execute([':pseudo' => $pseudo]);
        if ($stmt->fetchColumn() > 0) {
            return ['success' => false, 'error' => 'Ce pseudo est déjà pris.'];
        }

        // === ENREGISTREMENT ===
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $imgdefaut = 'public/uploads/profiles/default.png';

        $insertSql = 'INSERT INTO utilisateur (email, pseudo, mot_de_passe, image_profil) 
                  VALUES (:email, :pseudo, :mot_de_passe, :image_profil)';
        $stmt = $this->pdo->prepare($insertSql);
        $executed = $stmt->execute([
            ':email' => $email,
            ':pseudo' => $pseudo,
            ':mot_de_passe' => $hash,
            ':image_profil' => $imgdefaut
        ]);

        if (!$executed) {
            return ['success' => false, 'error' => "Erreur lors de l'enregistrement de l'utilisateur."];
        }

        // Création du portefeuille
        $newUserId = $this->pdo->lastInsertId();
        $walletSql = 'INSERT INTO portefeuille (capital_initial, id_utilisateur) VALUES (10000, :userId)';
        $stmtPf = $this->pdo->prepare($walletSql);

        if (!$stmtPf->execute([':userId' => $newUserId])) {
            return ['success' => false, 'error' => 'Erreur lors de la création du portefeuille.'];
        }

        return ['success' => true];
    }

    // Login : vérifie l'identifiant et retourne l'utilisateur si correct
    public function login($email, $password)
    {
        $sql = 'SELECT * FROM utilisateur WHERE email = :email';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            return $user;
        }
        return false;
    }

    // Méthode pour mettre à jour la bio, réseaux et l'image de profil de l'utilisateur
    public function updateProfile($userId, $pseudo, $bio, $imagePath, $instagram = null, $x = null, $telegram = null)
    {
        $sql = 'UPDATE utilisateur 
                SET pseudo = :pseudo,
                    bio = :bio, 
                    image_profil = :image_profil, 
                    instagram = :instagram, 
                    x = :x, 
                    telegram = :telegram 
                WHERE id_utilisateur = :id';

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':pseudo' => $pseudo,
            ':bio' => $bio,
            ':image_profil' => $imagePath,
            ':instagram' => $instagram,
            ':x' => $x,
            ':telegram' => $telegram,
            ':id' => $userId
        ]);
    }

    // (Optionnel) Méthode pour récupérer l'utilisateur par son ID
    public function getById($userId)
    {
        $sql = 'SELECT * FROM utilisateur WHERE id_utilisateur = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $userId]);
        return $stmt->fetch();
    }

    // Méthode pour récupérer tous les utilisateurs
    public function getAllIdUsers()
    {
        $sql = 'SELECT id_utilisateur FROM utilisateur';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour récupérer un utilisateur par son pseudo (toutes les infos sauf l'ID)
    public function getByPseudo($pseudo)
    {
        $sql = 'SELECT pseudo, id_utilisateur, image_profil, bio, instagram, x, telegram 
            FROM utilisateur 
            WHERE pseudo = :pseudo';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':pseudo' => $pseudo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupère tous les utilisateurs

    public function getAllUsers()
    {
        $sql = 'SELECT id_utilisateur, email, pseudo, role, bio, image_profil FROM utilisateur ORDER BY id_utilisateur DESC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteUser($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM utilisateur WHERE id_utilisateur = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function updateRole($id, $newRole)
    {
        $stmt = $this->pdo->prepare('UPDATE utilisateur SET role = :role WHERE id_utilisateur = :id');
        return $stmt->execute([
            ':id' => $id,
            ':role' => $newRole
        ]);
    }

    public function isPseudoTaken($pseudo, $currentUserId)
    {
        $sql = 'SELECT id_utilisateur FROM utilisateur WHERE pseudo = ? AND id_utilisateur != ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$pseudo, $currentUserId]);
        return $stmt->fetch() ? true : false;
    }

    public function searchUsers($term)
    {
        $stmt = $this->pdo->prepare('
        SELECT id_utilisateur, pseudo, email, role, bio, image_profil
        FROM utilisateur
        WHERE pseudo LIKE :term OR email LIKE :term
        ORDER BY id_utilisateur DESC
    ');
        $like = '%' . $term . '%';
        $stmt->execute([':term' => $like]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
