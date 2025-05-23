<?php
namespace App\Models;

use App\Models\Database;
use App\Utils\Logger;
use PDO;

/**
 * Classe gérant l'entité CryptoTrans et les opérations associées
 * sur les tables cryptotrans et cryptomarket.
 */
class CryptoTrans
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    /**
     * Récupère toutes les cryptos enregistrées dans cryptotrans
     * et les renvoie ordonnées par identifiant décroissant.
     *
     * @return array Tableau associatif de toutes les entrées cryptotrans.
     */
    public function getAll()
    {
        $stmt = $this->pdo->query('SELECT * FROM cryptotrans ORDER BY id_crypto_trans DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Crée un nouvel enregistrement de crypto dans cryptotrans et cryptomarket.
     *
     * @param string $code Code de la cryptomonnaie (ex: "BTC").
     * @return array ['success' => bool, 'error' => string?]
     */
    public function createCrypto($code)
    {
        $code = strtoupper(trim($code));
        if (empty($code)) {
            return ['success' => false, 'error' => 'Code de crypto manquant.'];
        }

        try {
            $this->pdo->beginTransaction();

            // Insérer dans cryptotrans
            $stmt1 = $this->pdo->prepare('INSERT INTO cryptotrans (code) VALUES (:code)');
            $stmt1->execute([':code' => $code]);

            // Insérer aussi dans cryptomarket avec valeurs par défaut
            $stmt2 = $this->pdo->prepare('
                INSERT INTO cryptomarket (code, categorie, prix_actuel, variation_24h, date_maj)
                VALUES (:code, "", 0, 0, NOW())
            ');
            $stmt2->execute([':code' => $code]);

            $this->pdo->commit();
            return ['success' => true];
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            Logger::error("Erreur ajout crypto trans + market : " . $e->getMessage());
            return ['success' => false, 'error' => "Erreur lors de l'ajout de la crypto."];
        }
    }


    /**
     * Supprime une crypto des tables cryptotrans et cryptomarket.
     *
     * @param int $id Identifiant dans cryptotrans.
     * @return array ['success' => bool, 'error' => string?]
     */
    public function deleteCrypto($id)
    {
        try {
            // 1. Récupérer le code associé à l'id
            $stmtCode = $this->pdo->prepare('SELECT code FROM cryptotrans WHERE id_crypto_trans = :id');
            $stmtCode->execute([':id' => $id]);
            $row = $stmtCode->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                return ['success' => false, 'error' => 'Crypto introuvable.'];
            }

            $code = $row['code'];

            $this->pdo->beginTransaction();

            // 2. Supprimer de cryptotrans
            $stmt1 = $this->pdo->prepare('DELETE FROM cryptotrans WHERE id_crypto_trans = :id');
            $stmt1->execute([':id' => $id]);

            // 3. Supprimer de cryptomarket (même code)
            $stmt2 = $this->pdo->prepare('DELETE FROM cryptomarket WHERE code = :code');
            $stmt2->execute([':code' => $code]);

            $this->pdo->commit();
            return ['success' => true];
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            Logger::error("Erreur suppression crypto trans + market : " . $e->getMessage());
            return ['success' => false, 'error' => "Erreur lors de la suppression de la crypto."];
        }
    }
}
