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

    // Met à jour les données de chaque crypto via l'API Binance
    public function updateFromBinance() {
        // Récupérer tous les enregistrements
        $stmt = $this->pdo->query("SELECT id_crypto_market, code FROM cryptomarket");
        $cryptos = $stmt->fetchAll();

        foreach ($cryptos as $crypto) {
            $symbol = $crypto['code'];
            // Appeler l'API Binance pour obtenir les données 24h pour le symbole
            $apiUrl = "https://api.binance.com/api/v3/ticker/24hr?symbol=" . $symbol;
            $data = $this->callBinanceApi($apiUrl);

            if ($data && isset($data['lastPrice']) && isset($data['priceChangePercent'])) {
                $lastPrice = $data['lastPrice'];
                $priceChangePercent = $data['priceChangePercent'];

                // Mettre à jour la table avec le nouveau prix, variation et date actuelle
                $updateStmt = $this->pdo->prepare(
                    "UPDATE cryptomarket 
                     SET prix_actuel = :prix_actuel, variation_24h = :variation_24h, date_maj = NOW() 
                     WHERE id_crypto_market = :id"
                );
                $updateStmt->execute([
                    ':prix_actuel'    => $lastPrice,
                    ':variation_24h'  => $priceChangePercent,
                    ':id'             => $crypto['id_crypto_market']
                ]);
            }
        }
    }

    // Fonction privée pour appeler l'API Binance
    private function callBinanceApi($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // Optionnel : définir un timeout
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            // En cas d'erreur, retourner false ou gérer l'erreur comme souhaité
            curl_close($curl);
            return false;
        }
        curl_close($curl);
        return json_decode($result, true);
    }
}
?>
