<?php
namespace App\Models;

use App\Models\Database;
use PDO;

/**
 * Classe gérant les opérations de lecture et de mise à jour
 * des données de marché des cryptomonnaies via l'API Binance.
 */
class CryptoMarket
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    /**
     * Récupère toutes les cryptomonnaies de la table cryptomarket.
     *
     * @return array Tableau associatif des entrées cryptomarket.
     */
    public function getAll()
    {
        $stmt = $this->pdo->query('SELECT id_crypto_market, code, prix_actuel, variation_24h, date_maj, categorie FROM cryptomarket');
        return $stmt->fetchAll();
    }

    /**
     * Récupère toutes les cryptomonnaies correspondant à une catégorie.
     *
     * @param string $cat Catégorie recherchée.
     * @return array Tableau associatif des entrées filtrées par catégorie.
     */
    public function getAllFromCat($cat)
    {
        $sql = "SELECT id_crypto_market, code, prix_actuel, variation_24h, date_maj 
                FROM cryptomarket 
                WHERE categorie LIKE CONCAT('%', :cat, '%');
            ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':cat' => $cat]);
        return $stmt->fetchAll();
    }

    /**
     * Met à jour les données de marché de toutes les cryptomonnaies
     * en parallèle via l'API Binance.
     *
     * @return void
     */
    public function updateFromBinance()
    {
        // Récupérer tous les enregistrements
        $stmt = $this->pdo->query('SELECT id_crypto_market, code FROM cryptomarket');
        $cryptos = $stmt->fetchAll();

        if (empty($cryptos)) {
            return;
        }

        // Initialiser le multi cURL
        $multiCurl = curl_multi_init();
        $curlHandles = [];

        // Préparez tous les handles de requête
        foreach ($cryptos as $crypto) {
            $symbol = $crypto['code'];
            $apiUrl = 'https://api.binance.com/api/v3/ticker/24hr?symbol=' . $symbol;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // Timeout de 5 secondes pour éviter les blocages prolongés
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_multi_add_handle($multiCurl, $ch);
            // Stocker le handle avec l'id de la crypto pour l'identifier plus tard
            $curlHandles[$crypto['id_crypto_market']] = $ch;
        }

        // Exécuter tous les handles en parallèle
        $running = null;
        do {
            curl_multi_exec($multiCurl, $running);
            curl_multi_select($multiCurl);
        } while ($running > 0);

        // Traiter les résultats et mettre à jour la base de données
        foreach ($curlHandles as $id => $ch) {
            $result = curl_multi_getcontent($ch);
            if ($result !== false) {
                $data = json_decode($result, true);
                if ($data && isset($data['lastPrice']) && isset($data['priceChangePercent'])) {
                    $lastPrice = $data['lastPrice'];
                    $priceChangePercent = $data['priceChangePercent'];

                    $updateStmt = $this->pdo->prepare(
                        'UPDATE cryptomarket 
                         SET prix_actuel = :prix_actuel, variation_24h = :variation_24h, date_maj = NOW() 
                         WHERE id_crypto_market = :id'
                    );
                    $updateStmt->execute([
                        ':prix_actuel' => $lastPrice,
                        ':variation_24h' => $priceChangePercent,
                        ':id' => $id
                    ]);
                }
            }
            // Retirer et fermer le handle
            curl_multi_remove_handle($multiCurl, $ch);
            curl_close($ch);
        }

        curl_multi_close($multiCurl);
    }

    /**
     * Appelle l'API Binance (ancienne méthode non utilisée dans updateFromBinance).
     *
     * @param string $url URL complète du point d'accès API.
     * @return array|false Données JSON décodées ou false en cas d'erreur cURL.
     */

    private function callBinanceApi($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            curl_close($curl);
            return false;
        }
        curl_close($curl);
        return json_decode($result, true);
    }


    /**
     * Crée une nouvelle cryptomonnaie dans cryptomarket et cryptotrans.
     *
     * @param string $code      Code de la cryptomonnaie.
     * @param string $categorie Catégorie associée.
     * @return array ['success' => bool, 'error' => string?]
     */
    public function createCrypto($code, $categorie)
    {
        // Nettoyage de l’entrée
        $code = strtoupper(trim($code));
        $categorie = trim($categorie);

        // Vérifier unicité du code dans cryptomarket
        $stmtCheck = $this->pdo->prepare('SELECT id_crypto_market FROM cryptomarket WHERE code = :code');
        $stmtCheck->execute([':code' => $code]);
        if ($stmtCheck->fetch()) {
            return ['success' => false, 'error' => 'Cette cryptomonnaie existe déjà.'];
        }

        // Démarre la transaction
        $this->pdo->beginTransaction();

        try {
            // Insertion dans cryptomarket
            $stmt1 = $this->pdo->prepare(
                'INSERT INTO cryptomarket (code, categorie, date_maj, prix_actuel, variation_24h)
             VALUES (:code, :categorie, NOW(), 0, 0)'
            );
            $stmt1->execute([
                ':code' => $code,
                ':categorie' => $categorie
            ]);

            // Insertion dans cryptotrans
            $stmt2 = $this->pdo->prepare('INSERT INTO cryptotrans (code) VALUES (:code)');
            $stmt2->execute([':code' => $code]);

            $this->pdo->commit();
            return ['success' => true];
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            error_log('Erreur lors de la création de la crypto : ' . $e->getMessage());
            return ['success' => false, 'error' => "Erreur interne lors de l'ajout."];
        }
    }


    /**
     * Supprime une cryptomonnaie dans cryptomarket et cryptotrans.
     *
     * @param int $id Identifiant de la crypto en cryptomarket.
     * @return array ['success' => bool, 'error' => string?]
     */
    public function deleteCrypto($id)
    {
        // Récupérer le code avant suppression (pour supprimer aussi dans cryptotrans)
        $stmtSelect = $this->pdo->prepare('SELECT code FROM cryptomarket WHERE id_crypto_market = :id');
        $stmtSelect->execute([':id' => $id]);
        $row = $stmtSelect->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return ['success' => false, 'error' => 'Crypto introuvable.'];
        }

        $code = $row['code'];

        try {
            $this->pdo->beginTransaction();

            // Supprimer d'abord de cryptomarket
            $stmtDeleteMarket = $this->pdo->prepare('DELETE FROM cryptomarket WHERE id_crypto_market = :id');
            $stmtDeleteMarket->execute([':id' => $id]);

            // Puis supprimer de cryptotrans (basé sur le code)
            $stmtDeleteTrans = $this->pdo->prepare('DELETE FROM cryptotrans WHERE code = :code');
            $stmtDeleteTrans->execute([':code' => $code]);

            $this->pdo->commit();
            return ['success' => true];
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            error_log('Erreur suppression crypto : ' . $e->getMessage());
            return ['success' => false, 'error' => 'Erreur lors de la suppression.'];
        }
    }
}
?>
