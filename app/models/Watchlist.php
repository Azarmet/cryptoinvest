<?php
namespace App\Models;

use App\Models\Database;
use PDO;

class Watchlist
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // Récupère la watchlist d'un utilisateur
    public function getWatchlist($userId)
    {
        $sql = 'SELECT cm.id_crypto_market, cm.code, cm.prix_actuel, cm.variation_24h, cm.date_maj
                FROM watchlist w
                JOIN cryptomarket cm ON w.id_crypto_market = cm.id_crypto_market
                WHERE w.id_utilisateur = :userId';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetchAll();
    }

    // Ajoute une cryptomonnaie à la watchlist de l'utilisateur
    public function add($userId, $cryptoMarketId)
    {
        // Vérifier si déjà présent
        $checkSql = 'SELECT * FROM watchlist WHERE id_utilisateur = :userId AND id_crypto_market = :cryptoMarketId';
        $stmt = $this->pdo->prepare($checkSql);
        $stmt->execute([':userId' => $userId, ':cryptoMarketId' => $cryptoMarketId]);
        if ($stmt->rowCount() > 0) {
            return false;
        }

        $sql = 'INSERT INTO watchlist (id_utilisateur, id_crypto_market) VALUES (:userId, :cryptoMarketId)';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':userId' => $userId, ':cryptoMarketId' => $cryptoMarketId]);
    }

    // Supprime une cryptomonnaie de la watchlist de l'utilisateur
    public function remove($userId, $cryptoMarketId)
    {
        $sql = 'DELETE FROM watchlist WHERE id_utilisateur = :userId AND id_crypto_market = :cryptoMarketId';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':userId' => $userId, ':cryptoMarketId' => $cryptoMarketId]);
    }
}
?>
