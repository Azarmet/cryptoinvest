<?php
namespace App\Models;

use App\Models\Database;
use PDO;

class Transaction
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getCryptoTrans()
    {
        $stmt = $this->pdo->query('SELECT code FROM cryptotrans');
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Ouvre une nouvelle position (long/short) pour l'utilisateur, sur BTCUSDT.
     * Le prix d'ouverture est récupéré directement via l'API Binance.
     * Cette méthode ne modifie pas la colonne capital_actuel.
     */
    public function openPosition($userId, $montant, $type, $cryptoCode = 'BTCUSDT')
    {
        // Récupérer le prix d'ouverture en temps réel via l'API Binance pour la crypto choisie
        $prixOuverture = $this->getCurrentPriceFromBinance($cryptoCode);
        if (!$prixOuverture) {
            return;  // ou gérer l'erreur
        }

        // Calculer la quantité investie
        $quantite = ($prixOuverture > 0) ? ($montant / $prixOuverture) : 0;

        // Insérer la transaction avec statut 'open'
        $sqlInsert = "
        INSERT INTO transaction
            (statut, date_ouverture, prix_ouverture, quantite, sens, id_portefeuille, id_crypto_trans)
        VALUES
            ('open', NOW(), :prixOuverture, :quantite, :sens,
             (SELECT id_portefeuille FROM portefeuille WHERE id_utilisateur = :userId LIMIT 1),
             (SELECT id_crypto_trans FROM cryptotrans WHERE code = :cryptoCode LIMIT 1)
            )
    ";
        $stmtInsert = $this->pdo->prepare($sqlInsert);
        $stmtInsert->execute([
            ':prixOuverture' => $prixOuverture,
            ':quantite' => $quantite,
            ':sens' => $type,  // "long" ou "short"
            ':userId' => $userId,
            ':cryptoCode' => $cryptoCode
        ]);
    }

    /**
     * Clôture une position 'open' et met à jour le portefeuille.
     * Le capital_actuel est mis à jour en ajoutant uniquement le PnL réalisé.
     */
    public function closePosition($idTransaction, $userId)
    {
        // Récupérer la transaction à clôturer appartenant à l'utilisateur,
        // en JOIGNANT également cryptotrans pour récupérer le champ code :
        $sql = "
            SELECT t.*, pf.id_utilisateur AS userOwner, c.code
            FROM transaction t
            JOIN portefeuille pf ON pf.id_portefeuille = t.id_portefeuille
            JOIN cryptotrans c ON c.id_crypto_trans = t.id_crypto_trans
            WHERE t.id_transaction = :id
              AND t.statut = 'open'
              AND pf.id_utilisateur = :userId
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $idTransaction, ':userId' => $userId]);
        $transaction = $stmt->fetch();

        if (!$transaction) {
            return;
        }

        // Récupérer le prix actuel en temps réel de la crypto concernée
        $prixCloture = $this->getCurrentPriceFromBinance($transaction['code']);
        if (!$prixCloture) {
            return;
        }

        // Calculer le PnL en fonction du type de position
        if ($transaction['sens'] === 'long') {
            $pnl = ($prixCloture - $transaction['prix_ouverture']) * $transaction['quantite'];
        } else {
            $pnl = ($transaction['prix_ouverture'] - $prixCloture) * $transaction['quantite'];
        }

        // Mettre à jour la transaction : statut 'close', enregistre prix_cloture, PnL et date de clôture
        $sqlUpdate = "
            UPDATE transaction
            SET statut = 'close',
                date_cloture = NOW(),
                prix_cloture = :prixCloture,
                pnl = :pnl
            WHERE id_transaction = :idTransaction
        ";
        $stmtUp = $this->pdo->prepare($sqlUpdate);
        $stmtUp->execute([
            ':prixCloture' => $prixCloture,
            ':pnl' => $pnl,
            ':idTransaction' => $idTransaction
        ]);

        // Mettre à jour le portefeuille (capital_actuel) en ajoutant le PnL réalisé
        $sqlUpdatePf = '
            UPDATE portefeuille
            SET capital_actuel = capital_actuel + :pnl
            WHERE id_utilisateur = :userId
        ';
        $stmtPf = $this->pdo->prepare($sqlUpdatePf);
        $stmtPf->execute([
            ':pnl' => $pnl,
            ':userId' => $userId
        ]);
    }

    /**
     * Récupère le prix actuel en temps réel pour un code donné en appelant l'API Binance.
     * @param string $code Le code de la cryptomonnaie (ex: 'BTCUSDT')
     * @return float|false Le prix actuel ou false en cas d'erreur.
     */
    private function getCurrentPriceFromBinance($code)
    {
        $apiUrl = 'https://api.binance.com/api/v3/ticker/24hr?symbol=' . $code;
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

    /**
     * Récupère les positions 'open' de l'utilisateur et met à jour dynamiquement le prix pour chaque position.
     */
    public function getOpenPositions($userId)
    {
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
            $prixActuel = $this->getCurrentPriceFromBinance($code);
            if (!$prixActuel) {
                $prixActuel = floatval($row['prix_ouverture']);
            }
            $pnl = ($row['sens'] === 'long')
                ? ($prixActuel - $row['prix_ouverture']) * $row['quantite']
                : ($row['prix_ouverture'] - $prixActuel) * $row['quantite'];
            $montantInvesti = $row['prix_ouverture'] * $row['quantite'];
            $roi = ($montantInvesti > 0) ? (($pnl / $montantInvesti) * 100) : 0;
            $positions[] = [
                'id_transaction' => $row['id_transaction'],
                'code' => $code,
                'sens' => $row['sens'],
                'prix_ouverture' => floatval($row['prix_ouverture']),
                'prix_actuel' => $prixActuel,
                'date_ouverture' => $row['date_ouverture'],
                'pnl' => round($pnl, 2),
                'roi' => round($roi, 2) . '%',
                'taille' => floatval($row['quantite'])
            ];
        }
        return $positions;
    }
}
