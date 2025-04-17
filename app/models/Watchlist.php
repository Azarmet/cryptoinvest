<?php
namespace App\Models;

use App\Models\Database;
use PDO;

/**
 * Classe gérant la watchlist d'un utilisateur,
 * permettant de récupérer, ajouter ou retirer des cryptomonnaies.
 */
class Watchlist
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    /**
     * Récupère la watchlist complète d'un utilisateur,
     * avec les données de marché associées pour chaque crypto.
     *
     * @param int $userId Identifiant de l'utilisateur.
     * @return array Tableau associatif des entrées de watchlist.
     */
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

    /**
     * Ajoute une cryptomonnaie à la watchlist d'un utilisateur,
     * si elle n'y figure pas déjà.
     *
     * @param int $userId         Identifiant de l'utilisateur.
     * @param int $cryptoMarketId Identifiant de la cryptomonnaie en market.
     * @return bool Vrai si l'ajout réussit, false sinon.
     */
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

    /**
     * Supprime une cryptomonnaie de la watchlist d'un utilisateur.
     *
     * @param int $userId         Identifiant de l'utilisateur.
     * @param int $cryptoMarketId Identifiant de la cryptomonnaie en market.
     * @return bool Vrai si la suppression réussit, false sinon.
     */
    public function remove($userId, $cryptoMarketId)
    {
        $sql = 'DELETE FROM watchlist WHERE id_utilisateur = :userId AND id_crypto_market = :cryptoMarketId';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':userId' => $userId, ':cryptoMarketId' => $cryptoMarketId]);
    }
}
?>
