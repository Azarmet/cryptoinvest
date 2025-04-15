<?php
namespace App\Models;

use App\Models\Database;
use PDO;

class Faq
{
        private $pdo;

        public function __construct()
        {
                $this->pdo = Database::getInstance()->getConnection();
        }

        //  Récupère toutes les FAQ
        public function getAll()
        {
                $stmt = $this->pdo->query('SELECT id_faq, question, reponse FROM faq ORDER BY id_faq DESC');
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        //  Recherche par mot-clé
        public function search($term)
        {
                $stmt = $this->pdo->prepare(
                        'SELECT id_faq, question, reponse 
                 FROM faq 
                 WHERE question LIKE :term1 OR reponse LIKE :term2 
                 ORDER BY id_faq DESC'
                );
                $likeTerm = '%' . $term . '%';
                $stmt->execute([
                        ':term1' => $likeTerm,
                        ':term2' => $likeTerm
                ]);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        //  Récupère une FAQ par ID
        public function getById($id)
        {
                $stmt = $this->pdo->prepare('SELECT * FROM faq WHERE id_faq = :id');
                $stmt->execute([':id' => $id]);
                return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        //  Crée une nouvelle FAQ
        public function createFaq($question, $reponse)
        {
                $validation = $this->validateFaqData($question, $reponse);
                if (!$validation['success'])
                        return $validation;

                $stmt = $this->pdo->prepare('INSERT INTO faq (question, reponse) VALUES (:question, :reponse)');
                $success = $stmt->execute([
                        ':question' => $question,
                        ':reponse' => $reponse
                ]);

                return ['success' => $success];
        }

        //  Met à jour une FAQ existante
        public function updateFaq($id, $question, $reponse)
        {
                $validation = $this->validateFaqData($question, $reponse);
                if (!$validation['success'])
                        return $validation;

                $stmt = $this->pdo->prepare('UPDATE faq SET question = :question, reponse = :reponse WHERE id_faq = :id');
                $success = $stmt->execute([
                        ':id' => $id,
                        ':question' => $question,
                        ':reponse' => $reponse
                ]);

                return ['success' => $success];
        }

        //  Supprime une FAQ
        public function deleteFaq($id)
        {
                $stmt = $this->pdo->prepare('DELETE FROM faq WHERE id_faq = :id');
                return $stmt->execute([':id' => $id]);
        }

        public function validateFaqData($question, $reponse)
        {
                if (empty(trim($question)) || empty(trim($reponse))) {
                        return ['success' => false, 'error' => 'Les champs ne peuvent pas être vides.'];
                }

                if (strlen($question) > 255) {
                        return ['success' => false, 'error' => 'La question ne peut pas dépasser 255 caractères.'];
                }

                return ['success' => true];
        }
}
?>
