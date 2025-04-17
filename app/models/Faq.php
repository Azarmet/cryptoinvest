<?php
namespace App\Models;

use App\Models\Database;
use PDO;

/**
 * Classe représentant une FAQ et gérant les opérations CRUD.
 */
class Faq
{
        private $pdo;

        public function __construct()
        {
                $this->pdo = Database::getInstance()->getConnection();
        }

        /**
         * Récupère toutes les FAQ ordonnées par ID descendant.
         *
         * @return array Tableau associatif des FAQ.
         */
        public function getAll()
        {
                $stmt = $this->pdo->query('SELECT id_faq, question, reponse FROM faq ORDER BY id_faq DESC');
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * Recherche des FAQ contenant le terme donné dans la question ou la réponse.
         *
         * @param string $term Terme de recherche.
         * @return array Tableau associatif des résultats de recherche.
         */
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

        /**
         * Récupère une FAQ par son identifiant.
         *
         * @param int $id Identifiant de la FAQ.
         * @return array|null La FAQ correspondante ou null si non trouvée.
         */
        public function getById($id)
        {
                $stmt = $this->pdo->prepare('SELECT * FROM faq WHERE id_faq = :id');
                $stmt->execute([':id' => $id]);
                return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        /**
         * Crée une nouvelle entrée FAQ après validation des données.
         *
         * @param string $question Texte de la question.
         * @param string $reponse  Texte de la réponse.
         * @return array Tableau associatif avec clé 'success' et éventuellement 'error'.
         */
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

        /**
         * Met à jour une FAQ existante après validation des données.
         *
         * @param int    $id       Identifiant de la FAQ à mettre à jour.
         * @param string $question Nouvelle question.
         * @param string $reponse  Nouvelle réponse.
         * @return array Tableau associatif avec clé 'success' et éventuellement 'error'.
         */
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

        /**
         * Supprime une FAQ par son identifiant.
         *
         * @param int $id Identifiant de la FAQ à supprimer.
         * @return bool Vrai si la suppression a réussi, sinon false.
         */
        public function deleteFaq($id)
        {
                $stmt = $this->pdo->prepare('DELETE FROM faq WHERE id_faq = :id');
                return $stmt->execute([':id' => $id]);
        }

        /**
         * Valide les données de question et de réponse pour la FAQ.
         *
         * @param string $question Question à valider.
         * @param string $reponse  Réponse à valider.
         * @return array Tableau associatif ['success' => bool, 'error' => string|null].
         */
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
