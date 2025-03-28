<?php
namespace App\Models;

use PDO;
use App\Models\Database;

class Faq {
    private $pdo;

    public function __construct(){
        $this->pdo = Database::getInstance()->getConnection();
    }

    // 🔍 Récupère toutes les FAQ
    public function getAll(){
        $stmt = $this->pdo->query("SELECT id_faq, question, reponse FROM faq ORDER BY id_faq DESC");
        return $stmt->fetchAll();
    }

    // 🔎 Recherche par mot-clé
    public function search($term){
        $stmt = $this->pdo->prepare("SELECT id_faq, question, reponse FROM faq WHERE question LIKE :term OR reponse LIKE :term ORDER BY id_faq DESC");
        $likeTerm = '%' . $term . '%';
        $stmt->execute([':term' => $likeTerm]);
        return $stmt->fetchAll();
    }

    // ✅ Récupère une FAQ par ID
    public function getById($id){
        $stmt = $this->pdo->prepare("SELECT * FROM faq WHERE id_faq = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ➕ Crée une nouvelle FAQ
    public function createFaq($question, $reponse){
        $stmt = $this->pdo->prepare("INSERT INTO faq (question, reponse) VALUES (:question, :reponse)");
        return $stmt->execute([
            ':question' => $question,
            ':reponse' => $reponse
        ]);
    }

    // ✏️ Met à jour une FAQ existante
    public function updateFaq($id, $question, $reponse){
        $stmt = $this->pdo->prepare("UPDATE faq SET question = :question, reponse = :reponse WHERE id_faq = :id");
        return $stmt->execute([
            ':id' => $id,
            ':question' => $question,
            ':reponse' => $reponse
        ]);
    }

    // ❌ Supprime une FAQ
    public function deleteFaq($id){
        $stmt = $this->pdo->prepare("DELETE FROM faq WHERE id_faq = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>
