<?php
namespace App\Models;

use PDO;
use App\Models\Database;

class Faq {
    private $pdo;

    public function __construct(){
         $this->pdo = Database::getInstance()->getConnection();
    }

    // Récupère toutes les FAQ
    public function getAll(){
         $stmt = $this->pdo->query("SELECT id_faq, question, reponse FROM faq ORDER BY id_faq DESC");
         return $stmt->fetchAll();
    }

    // Recherche des FAQ dont la question ou la réponse contient le terme
    public function search($term){
         $stmt = $this->pdo->prepare("SELECT id_faq, question, reponse FROM faq WHERE question LIKE :term OR reponse LIKE :term ORDER BY id_faq DESC");
         $likeTerm = '%' . $term . '%';
         $stmt->execute([':term' => $likeTerm]);
         return $stmt->fetchAll();
    }
}
?>
