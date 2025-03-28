<?php
namespace App\Models;

use PDO;
use App\Models\Database;

class CryptoTrans {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM cryptotrans ORDER BY id_crypto_trans DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createCrypto($code) {
        $stmt = $this->pdo->prepare("INSERT INTO cryptotrans (code) VALUES (:code)");
        return $stmt->execute([':code' => $code]);
    }

    public function deleteCrypto($id) {
        $stmt = $this->pdo->prepare("DELETE FROM cryptotrans WHERE id_crypto_trans = :id");
        return $stmt->execute([':id' => $id]);
    }
}
