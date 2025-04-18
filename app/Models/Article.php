<?php
namespace App\Models;

use App\Models\Database;
use PDO;

/**
 * Classe gérant les opérations CRUD et la pagination des articles.
 */
class Article
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    /**
     * Compte le nombre total d'articles publiés,
     * avec filtrage optionnel par catégorie et terme de recherche.
     *
     * @param string|null $categorie Filtre sur la catégorie (null ou 'tous' = tous).
     * @param string|null $search    Terme à rechercher dans le titre ou le contenu.
     * @return int Nombre total d'articles correspondants.
     */
    public function countArticles($categorie = null, $search = null)
    {
        $sql = "SELECT COUNT(*) as total FROM article WHERE statut = 'publié'";
        $params = [];
        if (!empty($categorie) && strtolower($categorie) != 'tous') {
            $sql .= ' AND categorie = :categorie';
            $params[':categorie'] = $categorie;
        }
        if (!empty($search)) {
            $sql .= ' AND (titre LIKE :search1 OR contenu LIKE :search2)';
            $params[':search1'] = '%' . $search . '%';
            $params[':search2'] = '%' . $search . '%';
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int) $row['total'] : 0;
    }

    /**
     * Récupère un article par son identifiant.
     *
     * @param int $id Identifiant de l'article.
     * @return array|false Tableau associatif de l'article ou false si non trouvé.
     */
    public function getById($id)
    {
        $sql = 'SELECT * FROM article WHERE id_article = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère une liste d'articles avec pagination, tri par date,
     * et filtrage optionnel par catégorie et recherche.
     *
     * @param string|null $categorie Filtre sur la catégorie.
     * @param string|null $search    Terme à rechercher dans titre/contenu.
     * @param int         $limit     Nombre maximal d'articles à retourner.
     * @param int         $offset    Décalage pour la pagination.
     * @return array Tableau associatif des articles.
     */
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

    /**
     * Idem getArticles mais restreint aux articles au statut 'publié'.
     *
     * @param string|null $categorie Filtre catégorie.
     * @param string|null $search    Terme de recherche.
     * @param int         $limit     Nombre d'articles.
     * @param int         $offset    Décalage.
     * @return array Articles publiés.
     */
    public function getArticlesPublie($categorie = null, $search = null, $limit = 100, $offset = 0)
    {
        $sql = "SELECT * FROM article WHERE statut = 'publié'";
        $params = [];

        if (!empty($categorie) && strtolower($categorie) != 'tous') {
            $sql .= ' AND categorie = :categorie';
            $params[':categorie'] = $categorie;
        }

        if (!empty($search)) {
            // On utilise deux noms distincts pour les deux LIKE
            $sql .= ' AND (titre LIKE :search1 OR contenu LIKE :search2)';
            $params[':search1'] = '%' . $search . '%';
            $params[':search2'] = '%' . $search . '%';
        }

        $sql .= ' ORDER BY date_publication DESC LIMIT :limit OFFSET :offset';
        $stmt = $this->pdo->prepare($sql);

        // Liaison des paramètres dynamiques
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        // Liaison des paramètres de pagination
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crée un nouvel article après validation des données.
     *
     * @param array $data Données de l'article (titre, contenu, auteur, catégorie, statut, image).
     * @return array ['success'=>bool, 'error'=>string?]
     */
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

    /**
     * Met à jour un article existant après validation.
     *
     * @param int   $id   Identifiant de l'article.
     * @param array $data Données à mettre à jour.
     * @return array ['success'=>bool, 'error'=>string?]
     */
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

    /**
     * Supprime un article par son identifiant.
     *
     * @param int $id Identifiant de l'article à supprimer.
     * @return bool Vrai si la suppression a réussi, sinon false.
     */
    public function deleteArticle($id)
    {
        $sql = 'DELETE FROM article WHERE id_article = :id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Valide les données d'un article avant insertion ou mise à jour.
     *
     * @param array $data Données de l'article.
     * @return array ['success'=>bool, 'error'=>string?]
     */
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
        if (!empty($data['image']) && !preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $data['image'])) {
            return ['success' => false, 'error' => "Le format d'image est invalide."];
        }

        return ['success' => true];
    }
}
?>
