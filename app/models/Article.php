<?php
namespace App\Models;

use App\Models\Database;
use PDO;

class Article
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // Compte le nombre total d'articles (filtrés si besoin)
    public function countArticles($categorie = null, $search = null)
    {
        $sql = "SELECT COUNT(*) as total FROM article WHERE statut = 'publié'";
        $params = [];
        if (!empty($categorie) && strtolower($categorie) != 'tous') {
            $sql .= ' AND categorie = :categorie';
            $params[':categorie'] = $categorie;
        }
        if (!empty($search)) {
            $sql .= ' AND (titre LIKE :search OR contenu LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int) $row['total'] : 0;
    }

    public function getById($id)
    {
        $sql = 'SELECT * FROM article WHERE id_article = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupère les articles avec filtrage, pagination, et tri par date
    public function getArticles($categorie = null, $search = null, $limit = 100, $offset = 0)
    {
        $sql = 'SELECT * FROM article WHERE 1';  // <- Ajouté pour permettre les AND suivants
        $params = [];

        if (!empty($categorie) && strtolower($categorie) !== 'tous') {
            $sql .= ' AND categorie = :categorie';
            $params[':categorie'] = $categorie;
        }

        if (!empty($search)) {
            $sql .= ' AND (titre LIKE :search OR contenu LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }

        $sql .= ' ORDER BY date_publication DESC LIMIT :limit OFFSET :offset';

        $stmt = $this->pdo->prepare($sql);

        // Liaison des paramètres dynamiques
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        // Liaison des paramètres de pagination (forcés en int)
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère les articles avec filtrage, pagination, et tri par date
    public function getArticlesPublie($categorie = null, $search = null, $limit = 100, $offset = 0)
    {
        $sql = "SELECT * FROM article WHERE statut = 'publié'";
        $params = [];
        if (!empty($categorie) && strtolower($categorie) != 'tous') {
            $sql .= ' AND categorie = :categorie';
            $params[':categorie'] = $categorie;
        }
        if (!empty($search)) {
            $sql .= ' AND (titre LIKE :search OR contenu LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }
        $sql .= ' ORDER BY date_publication DESC LIMIT :limit OFFSET :offset';
        $stmt = $this->pdo->prepare($sql);
        // Lier les paramètres existants
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createArticle($data)
    {
        $validation = $this->validateArticleData($data);
        if (!$validation['success']) {
            return $validation;
        }

        $sql = 'INSERT INTO article (titre, contenu, id_auteur, categorie, statut, date_publication, image)
            VALUES (:titre, :contenu, :auteur, :categorie, :statut, NOW(), :image)';
        $stmt = $this->pdo->prepare($sql);
        $success = $stmt->execute([
            ':titre' => $data['titre'],
            ':contenu' => $data['contenu'],
            ':auteur' => $data['auteur'],
            ':categorie' => $data['categorie'],
            ':statut' => $data['statut'],
            ':image' => $data['image']
        ]);

        return ['success' => $success];
    }

    public function updateArticle($id, $data)
    {
        $validation = $this->validateArticleData($data);
        if (!$validation['success']) {
            return $validation;
        }

        $sql = 'UPDATE article SET titre = :titre, contenu = :contenu, id_auteur = :auteur,
            categorie = :categorie, statut = :statut, image = :image WHERE id_article = :id';
        $stmt = $this->pdo->prepare($sql);
        $success = $stmt->execute([
            ':titre' => $data['titre'],
            ':contenu' => $data['contenu'],
            ':auteur' => $data['auteur'],
            ':categorie' => $data['categorie'],
            ':statut' => $data['statut'],
            ':image' => $data['image'],
            ':id' => $id
        ]);

        return ['success' => $success];
    }

    public function deleteArticle($id)
    {
        $sql = 'DELETE FROM article WHERE id_article = :id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    private function validateArticleData(array $data)
    {
        if (empty($data['titre']) || strlen($data['titre']) > 200) {
            return ['success' => false, 'error' => 'Le titre est requis et doit contenir moins de 200 caractères.'];
        }

        if (empty($data['contenu'])) {
            return ['success' => false, 'error' => 'Le contenu ne peut pas être vide.'];
        }

        if (!in_array($data['statut'], ['publié', 'brouillon'])) {
            return ['success' => false, 'error' => 'Le statut est invalide.'];
        }

        // Optionnel : validation de l'image (URL ou fichier)
        if (!empty($data['image']) && !preg_match('/\.(jpg|jpeg|png|gif)$/i', $data['image'])) {
            return ['success' => false, 'error' => "Le format d'image est invalide."];
        }

        return ['success' => true];
    }
}
?>
