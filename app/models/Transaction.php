<?php
namespace App\Models;

use PDO;
use App\Models\Database;

class Transaction {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    /**
     * Ouvre une nouvelle position (long/short) pour l'utilisateur, sur BTCUSDT.
     */
    public function openPosition($userId, $montant, $type) {
        // Récupérer le prix actuel du BTCUSDT depuis Binance
        $prixOuverture = $this->getCurrentPriceFromBinance('BTCUSDT');
        if (!$prixOuverture) {
            // Gestion d'erreur, par exemple retourner ou afficher un message
            return;
        }

        $quantite = ($prixOuverture > 0) ? ($montant / $prixOuverture) : 0;

        // Insérer la transaction
        $sqlInsert = "
            INSERT INTO transaction
                (statut, date_ouverture, prix_ouverture, quantite, sens, id_portefeuille, id_crypto_trans)
            VALUES
                ('open', NOW(), :prixOuverture, :quantite, :sens,
                 (SELECT id_portefeuille FROM portefeuille WHERE id_utilisateur=:userId LIMIT 1),
                 (SELECT id_crypto_trans FROM cryptotrans WHERE code='BTCUSDT' LIMIT 1)
                )
        ";
        $stmtInsert = $this->pdo->prepare($sqlInsert);
        $stmtInsert->execute([
            ':prixOuverture' => $prixOuverture,
            ':quantite'      => $quantite,
            ':sens'          => $type,
            ':userId'        => $userId
        ]);
    }

    /**
     * Clôture une position 'open' et met à jour le PnL.
     */
    public function closePosition($idTransaction, $userId) {
        $sql = "
            SELECT t.*, pf.id_utilisateur as userOwner
            FROM transaction t
            JOIN portefeuille pf ON pf.id_portefeuille = t.id_portefeuille
            WHERE t.id_transaction = :id
              AND t.statut='open'
              AND pf.id_utilisateur = :userId
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $idTransaction, ':userId' => $userId]);
        $transaction = $stmt->fetch();

        if (!$transaction) {
            return;
        }

        // Récupérer le prix actuel du BTCUSDT
        $prixCloture = $this->getCurrentPriceFromBinance('BTCUSDT');
        if (!$prixCloture) {
            return;
        }

        $pnl = 0;
        if ($transaction['sens'] === 'long') {
            $pnl = ($prixCloture - $transaction['prix_ouverture']) * $transaction['quantite'];
        } else {
            $pnl = ($transaction['prix_ouverture'] - $prixCloture) * $transaction['quantite'];
        }

        $sqlUpdate = "
            UPDATE transaction
            SET statut='close',
                date_cloture = NOW(),
                prix_cloture = :prixCloture,
                pnl = :pnl
            WHERE id_transaction = :idTransaction
        ";
        $stmtUp = $this->pdo->prepare($sqlUpdate);
        $stmtUp->execute([
            ':prixCloture'   => $prixCloture,
            ':pnl'           => $pnl,
            ':idTransaction' => $idTransaction
        ]);
    }

    /**
     * Récupère les positions 'open' de l'utilisateur et met à jour dynamiquement le prix pour chaque crypto.
     */
    public function getOpenPositions($userId) {
        $sql = "
            SELECT t.id_transaction, t.prix_ouverture, t.quantite, t.date_ouverture, t.sens,
                   c.code
            FROM transaction t
            JOIN portefeuille pf ON pf.id_portefeuille = t.id_portefeuille
            JOIN utilisateur u ON u.id_utilisateur = pf.id_utilisateur
            JOIN cryptotrans c ON c.id_crypto_trans = t.id_crypto_trans
            WHERE t.statut = 'open'
              AND u.id_utilisateur = :userId
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$rows) {
            return [];
        }

        $positions = [];
        foreach ($rows as $row) {
            $code = $row['code'];
            // Récupérer le prix actuel en interrogeant directement Binance pour ce code
            $prixActuel = $this->getCurrentPriceFromBinance($code);
            if (!$prixActuel) {
                $prixActuel = floatval($row['prix_ouverture']);
            }
            $pnl = 0;
            if ($row['sens'] === 'long') {
                $pnl = ($prixActuel - $row['prix_ouverture']) * $row['quantite'];
            } else {
                $pnl = ($row['prix_ouverture'] - $prixActuel) * $row['quantite'];
            }
            $montantInvesti = $row['prix_ouverture'] * $row['quantite'];
            $roi = ($montantInvesti > 0) ? (($pnl / $montantInvesti) * 100) : 0;

            $positions[] = [
                'id_transaction' => $row['id_transaction'],
                'code'           => $code,
                'sens'           => $row['sens'],
                'prix_ouverture' => floatval($row['prix_ouverture']),
                'prix_actuel'    => $prixActuel,
                'date_ouverture' => $row['date_ouverture'],
                'pnl'            => round($pnl, 2),
                'roi'            => round($roi, 2) . '%'
            ];
        }
        return $positions;
    }

    /**
     * Récupère le prix actuel en temps réel pour un code donné en appelant l'API Binance.
     * @param string $code - Le code de la cryptomonnaie (ex: 'BTCUSDT')
     * @return float|false - Le prix actuel ou false en cas d'erreur.
     */
    private function getCurrentPriceFromBinance($code) {
        $apiUrl = "https://api.binance.com/api/v3/ticker/24hr?symbol=" . $code;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $apiUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            curl_close($curl);
            return false;
        }
        curl_close($curl);
        $data = json_decode($result, true);
        if ($data && isset($data['lastPrice'])) {
            return floatval($data['lastPrice']);
        }
        return false;
    }
}
