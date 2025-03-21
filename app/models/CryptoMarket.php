<?php
namespace App\Models;

use PDO;
use App\Models\Database;

class CryptoMarket {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT id_crypto_market, code, prix_actuel, variation_24h, date_maj FROM cryptomarket");
        return $stmt->fetchAll();
    }
}
?>
