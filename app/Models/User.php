<?php
namespace App\Models;

use App\Models\Database;
use PDO;

/**
 * Classe représentant un utilisateur et gérant l'enregistrement, la connexion,
 * et les opérations CRUD sur les profils utilisateurs.
 */
class User
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    /**
     * Inscription d'un nouvel utilisateur.
     *
     * Valide les données (email, pseudo, mot de passe), vérifie l'unicité
     * de l'email et du pseudo, hash le mot de passe, crée l'utilisateur
     * et initialise son portefeuille.
     *
     * @param string $email    Adresse e-mail de l'utilisateur.
     * @param string $pseudo   Pseudo choisi (3-10 caractères alphanumériques ou underscore).
     * @param string $password Mot de passe (minimum 8 caractères).
     * @return array ['success' => bool, 'error' => string?]
     */
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
        $imgdefaut = 'public/uploads/profiles/default.webp';

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

    /**
     * Authentification de l'utilisateur.
     *
     * Vérifie l'adresse e-mail et le mot de passe.
     *
     * @param string $email    Adresse e-mail.
     * @param string $password Mot de passe.
     * @return array|false Tableau de l'utilisateur ou false en cas d'échec.
     */
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

    /**
     * Met à jour le profil de l'utilisateur :
     * pseudo, bio, image, et liens vers les réseaux sociaux.
     *
     * @param int         $userId    Identifiant de l'utilisateur.
     * @param string      $pseudo    Nouveau pseudo.
     * @param string      $bio       Biographie.
     * @param string      $imagePath Chemin de la nouvelle image de profil.
     * @param string|null $instagram Lien Instagram (optionnel).
     * @param string|null $x         Lien X/Twitter (optionnel).
     * @param string|null $telegram  Lien Telegram (optionnel).
     * @return bool Vrai si la mise à jour a réussi, sinon false.
     */
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

    /**
     * Récupère un utilisateur par son identifiant.
     *
     * @param int $userId Identifiant de l'utilisateur.
     * @return array|false Tableau associatif de l'utilisateur ou false si non trouvé.
     */
    public function getById($userId)
    {
        $sql = 'SELECT * FROM utilisateur WHERE id_utilisateur = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $userId]);
        return $stmt->fetch();
    }

    /**
     * Récupère la liste de tous les identifiants d'utilisateurs.
     *
     * @return array Tableau d'associatifs avec la clé 'id_utilisateur'.
     */
    public function getAllIdUsers()
    {
        $sql = 'SELECT id_utilisateur FROM utilisateur';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un utilisateur par son pseudo (sans l'ID).
     *
     * @param string $pseudo Pseudo de l'utilisateur.
     * @return array|false Tableau associatif de l'utilisateur ou false si non trouvé.
     */
    public function getByPseudo($pseudo)
    {
        $sql = 'SELECT pseudo, id_utilisateur, image_profil, bio, instagram, x, telegram 
            FROM utilisateur 
            WHERE pseudo = :pseudo';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':pseudo' => $pseudo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère tous les utilisateurs (pour l'administration).
     *
     * @return array Tableau associatif des utilisateurs.
     */
    public function getAllUsers()
    {
        $sql = 'SELECT id_utilisateur, email, pseudo, role, bio, image_profil FROM utilisateur ORDER BY id_utilisateur DESC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Supprime un utilisateur par son identifiant.
     *
     * @param int $id Identifiant de l'utilisateur.
     * @return bool Vrai si la suppression a réussi, sinon false.
     */
    public function deleteUser($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM utilisateur WHERE id_utilisateur = :id');
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Met à jour le rôle d'un utilisateur.
     *
     * @param int    $id      Identifiant de l'utilisateur.
     * @param string $newRole Nouveau rôle (ex: 'admin', 'user').
     * @return bool Vrai si la mise à jour a réussi, sinon false.
     */
    public function updateRole($id, $newRole)
    {
        $stmt = $this->pdo->prepare('UPDATE utilisateur SET role = :role WHERE id_utilisateur = :id');
        return $stmt->execute([
            ':id' => $id,
            ':role' => $newRole
        ]);
    }

    /**
     * Vérifie si un pseudo est déjà pris par un autre utilisateur.
     *
     * @param string $pseudo         Pseudo à tester.
     * @param int    $currentUserId  Identifiant de l'utilisateur courant.
     * @return bool Vrai si le pseudo est pris, sinon false.
     */
    public function isPseudoTaken($pseudo, $currentUserId)
    {
        $sql = 'SELECT id_utilisateur FROM utilisateur WHERE pseudo = ? AND id_utilisateur != ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$pseudo, $currentUserId]);
        return $stmt->fetch() ? true : false;
    }

    /**
     * Recherche des utilisateurs par pseudo ou email.
     *
     * @param string $term Terme de recherche.
     * @return array Tableau associatif des utilisateurs correspondants.
     */
    public function searchUsers($term)
    {
        $sql = '
        SELECT id_utilisateur, pseudo, email, role, bio, image_profil
        FROM utilisateur
        WHERE pseudo LIKE :term1 OR email LIKE :term2
        ORDER BY id_utilisateur DESC
    ';
        $stmt = $this->pdo->prepare($sql);
        $like = '%' . $term . '%';
        $stmt->execute([
            ':term1' => $like,
            ':term2' => $like
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
