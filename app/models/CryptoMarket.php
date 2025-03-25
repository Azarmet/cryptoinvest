<?php
namespace App\Models;

use PDO;
use App\Models\Database;

class CryptoMarket {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // Récupère toutes les cryptomonnaies de la table
    public function getAll() {
        $stmt = $this->pdo->query("SELECT id_crypto_market, code, prix_actuel, variation_24h, date_maj FROM cryptomarket");
        return $stmt->fetchAll();
    }
    
    // Récupère toutes les cryptomonnaies de la table
    public function getAllFromCat($cat) {
        $sql = "SELECT id_crypto_market, code, prix_actuel, variation_24h, date_maj 
                FROM cryptomarket 
                WHERE categorie LIKE CONCAT('%', :cat, '%');
            ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':cat' => $cat]);
        return $stmt->fetchAll();
    }

    // Met à jour les données de chaque crypto via l'API Binance en parallèle
    public function updateFromBinance() {
        // Récupérer tous les enregistrements
        $stmt = $this->pdo->query("SELECT id_crypto_market, code FROM cryptomarket");
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
            $apiUrl = "https://api.binance.com/api/v3/ticker/24hr?symbol=" . $symbol;
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
                        "UPDATE cryptomarket 
                         SET prix_actuel = :prix_actuel, variation_24h = :variation_24h, date_maj = NOW() 
                         WHERE id_crypto_market = :id"
                    );
                    $updateStmt->execute([
                        ':prix_actuel'    => $lastPrice,
                        ':variation_24h'  => $priceChangePercent,
                        ':id'             => $id
                    ]);
                }
            }
            // Retirer et fermer le handle
            curl_multi_remove_handle($multiCurl, $ch);
            curl_close($ch);
        }

        curl_multi_close($multiCurl);
    }

    // Fonction privée pour appeler l'API Binance (ancienne méthode, non utilisée ici)
    private function callBinanceApi($url) {
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
}
?>
