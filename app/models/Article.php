<?php
namespace App\Models;

use PDO;
use App\Models\Database;

class Article {
    private $pdo;

    public function __construct(){
         $this->pdo = Database::getInstance()->getConnection();
    }

    // Compte le nombre total d'articles (filtrés si besoin)
    public function countArticles($categorie = null, $search = null) {
         $sql = "SELECT COUNT(*) as total FROM article WHERE statut = 'publié'";
         $params = [];
         if(!empty($categorie) && strtolower($categorie) != 'tous'){
             $sql .= " AND categorie = :categorie";
             $params[':categorie'] = $categorie;
         }
         if(!empty($search)){
             $sql .= " AND (titre LIKE :search OR contenu LIKE :search)";
             $params[':search'] = '%' . $search . '%';
         }
         $stmt = $this->pdo->prepare($sql);
         $stmt->execute($params);
         $row = $stmt->fetch(PDO::FETCH_ASSOC);
         return $row ? (int)$row['total'] : 0;
    }


    public function getById($id) {
        $sql = "SELECT * FROM article WHERE id_article = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    // Récupère les articles avec filtrage, pagination, et tri par date
    public function getArticles($categorie = null, $search = null, $limit = 10, $offset = 0) {
         $sql = "SELECT * FROM article WHERE statut = 'publié'";
         $params = [];
         if(!empty($categorie) && strtolower($categorie) != 'tous'){
             $sql .= " AND categorie = :categorie";
             $params[':categorie'] = $categorie;
         }
         if(!empty($search)){
             $sql .= " AND (titre LIKE :search OR contenu LIKE :search)";
             $params[':search'] = '%' . $search . '%';
         }
         $sql .= " ORDER BY date_publication DESC LIMIT :limit OFFSET :offset";
         $stmt = $this->pdo->prepare($sql);
         // Lier les paramètres existants
         foreach($params as $key => $value) {
             $stmt->bindValue($key, $value);
         }
         $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
         $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
         $stmt->execute();
         return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
