<?php
namespace App\Models;

use App\Models\Database;
use PDO;

/**
 * Classe gérant les opérations sur les transactions de trading :
 * ouverture, clôture, et récupération des positions.
 */
class Transaction
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    /**
     * Récupère tous les codes de cryptomonnaies disponibles dans la table cryptotrans.
     *
     * @return string[] Tableau de codes (ex. "BTCUSDT").
     */
    public function getCryptoTrans()
    {
        $stmt = $this->pdo->query('SELECT code FROM cryptotrans');
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Ouvre une nouvelle position (long ou short) pour une cryptomonnaie.
     * Ne met pas à jour le capital_actuel du portefeuille.
     *
     * @param int    $userId     Identifiant de l'utilisateur.
     * @param float  $montant    Montant en devise à investir.
     * @param string $type       'long' ou 'short'.
     * @param string $cryptoCode Code de la crypto (par défaut 'BTCUSDT').
     * @return array ['success' => bool, 'error' => string?]
     */
    public function openPosition($userId, $montant, $type, $cryptoCode = 'BTCUSDT')
    {
        // 1. Valider les données d'entrée
        if (!is_numeric($montant) || $montant <= 0) {
            return ['success' => false, 'error' => 'Le montant est invalide.'];
        }

        if (!in_array($type, ['long', 'short'])) {
            return ['success' => false, 'error' => 'Le type de position est invalide.'];
        }

        // 2. Vérifier que la crypto existe dans cryptotrans
        $checkCrypto = $this->pdo->prepare('SELECT id_crypto_trans FROM cryptotrans WHERE code = :code LIMIT 1');
        $checkCrypto->execute([':code' => $cryptoCode]);
        $cryptoRow = $checkCrypto->fetch();
        if (!$cryptoRow) {
            return ['success' => false, 'error' => 'Crypto inconnue.'];
        }

        // 3. Récupérer le prix d’ouverture via l’API Binance
        $prixOuverture = $this->getCurrentPriceFromBinance($cryptoCode);
        if (!$prixOuverture || !is_numeric($prixOuverture) || $prixOuverture <= 0) {
            return ['success' => false, 'error' => 'Erreur lors de la récupération du prix de la crypto.'];
        }

        // 4. Calcul de la quantité
        $quantite = $montant / $prixOuverture;

        // 5. Vérifier l’existence du portefeuille utilisateur
        $pfStmt = $this->pdo->prepare('SELECT id_portefeuille FROM portefeuille WHERE id_utilisateur = :uid LIMIT 1');
        $pfStmt->execute([':uid' => $userId]);
        $pfRow = $pfStmt->fetch();
        if (!$pfRow) {
            return ['success' => false, 'error' => 'Portefeuille non trouvé pour cet utilisateur.'];
        }

        // 6. Insertion dans la table transaction
        $sqlInsert = "
        INSERT INTO transaction (
            statut, date_ouverture, prix_ouverture, quantite, sens,
            id_portefeuille, id_crypto_trans
        )
        VALUES (
            'open', NOW(), :prixOuverture, :quantite, :sens,
            :id_portefeuille, :id_crypto_trans
        )
    ";

        $stmtInsert = $this->pdo->prepare($sqlInsert);
        $success = $stmtInsert->execute([
            ':prixOuverture' => $prixOuverture,
            ':quantite' => $quantite,
            ':sens' => $type,
            ':id_portefeuille' => $pfRow['id_portefeuille'],
            ':id_crypto_trans' => $cryptoRow['id_crypto_trans']
        ]);

        if (!$success) {
            return ['success' => false, 'error' => 'Erreur lors de l\'ouverture de la position.'];
        }

        return ['success' => true];
    }


    /**
     * Clôture une position ouverte et met à jour le capital_actuel du portefeuille.
     *
     * @param int $idTransaction Identifiant de la transaction à clôturer.
     * @param int $userId        Identifiant de l'utilisateur.
     * @return void
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
     * Récupère le prix actuel pour une crypto donnée en appelant l'API Binance.
     *
     * @param string $code Code de la crypto (ex. 'BTCUSDT').
     * @return float|false Prix actuel ou false en cas d'erreur.
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
     * Récupère les positions ouvertes de l'utilisateur et calcule dynamiquement
     * le PnL, ROI, et autres métriques pour chaque position.
     *
     * @param int $userId Identifiant de l'utilisateur.
     * @return array Tableau de positions avec détails et statistiques.
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


    /**
     * Récupère des statistiques pour le dashboard utilisateur :
     * nombre total, gagnantes, perdantes, pnl moyen, temps moyen, etc.
     *
     * @param int $userId Identifiant de l'utilisateur.
     * @return array|null Tableau associatif de stats ou null si aucun portefeuille.
     */
    public function getDashboardStats($userId)
    {
        // 1. Trouver l'id_portefeuille correspondant
        $stmt = $this->pdo->prepare('SELECT id_portefeuille FROM portefeuille WHERE id_utilisateur = :userId LIMIT 1');
        $stmt->execute(['userId' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;  // ou [] pour éviter les erreurs plus loin
        }

        $portefeuilleId = $result['id_portefeuille'];

        // 2. Calcul des stats à partir de l'id_portefeuille
        $sql = "
            SELECT 
                COUNT(*) as total,
                IFNULL(SUM(CASE WHEN pnl > 0 THEN 1 ELSE 0 END), 0) as gagnantes,
                IFNULL(SUM(CASE WHEN pnl <= 0 THEN 1 ELSE 0 END), 0) as perdantes,
                IFNULL(SUM(pnl) / NULLIF(COUNT(*), 0), 0) as pnl_moyen,
                IFNULL(AVG(TIMESTAMPDIFF(HOUR, date_ouverture, NOW())), 0) as temps_moyen_heures,
                IFNULL(SUM(CASE WHEN sens = 'long' THEN 1 ELSE 0 END), 0) as total_long,
                IFNULL(SUM(CASE WHEN sens = 'short' THEN 1 ELSE 0 END), 0) as total_short,
                IFNULL(SUM(CASE WHEN sens = 'long' AND pnl > 0 THEN 1 ELSE 0 END), 0) as longs_gagnants,
                IFNULL(SUM(CASE WHEN sens = 'short' AND pnl > 0 THEN 1 ELSE 0 END), 0) as shorts_gagnants,
                IFNULL(COUNT(*) / NULLIF(TIMESTAMPDIFF(DAY, MIN(date_ouverture), NOW()) / 30.0, 0), 0) as tx_par_mois
            FROM transaction
            WHERE id_portefeuille = :pid AND date_cloture IS NOT NULL
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['pid' => $portefeuilleId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Récupère les mêmes statistiques que getDashboardStats mais par pseudo utilisateur.
     *
     * @param string $pseudo Pseudo de l'utilisateur.
     * @return array|null Tableau de stats ou null si utilisateur/portefeuille introuvable.
     */
    public function getProfilboardStatsByPseudo($pseudo)
    {
        // 1. Trouver l'id_utilisateur via le pseudo
        $stmt = $this->pdo->prepare('SELECT id_utilisateur FROM utilisateur WHERE pseudo = :pseudo LIMIT 1');
        $stmt->execute(['pseudo' => $pseudo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return null;  // Aucun utilisateur trouvé
        }

        $userId = $user['id_utilisateur'];

        // 2. Trouver l'id_portefeuille lié à cet utilisateur
        $stmt = $this->pdo->prepare('SELECT id_portefeuille FROM portefeuille WHERE id_utilisateur = :userId LIMIT 1');
        $stmt->execute(['userId' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        $portefeuilleId = $result['id_portefeuille'];

        // 3. Récupérer les stats sur les transactions de ce portefeuille
        $sql = "
            SELECT 
                COUNT(*) as total,
                IFNULL(SUM(CASE WHEN pnl > 0 THEN 1 ELSE 0 END), 0) as gagnantes,
                IFNULL(SUM(CASE WHEN pnl <= 0 THEN 1 ELSE 0 END), 0) as perdantes,
                IFNULL(SUM(pnl) / NULLIF(COUNT(*), 0), 0) as pnl_moyen,
                IFNULL(AVG(TIMESTAMPDIFF(HOUR, date_ouverture, NOW())), 0) as temps_moyen_heures,
                IFNULL(SUM(CASE WHEN sens = 'long' THEN 1 ELSE 0 END), 0) as total_long,
                IFNULL(SUM(CASE WHEN sens = 'short' THEN 1 ELSE 0 END), 0) as total_short,
                IFNULL(SUM(CASE WHEN sens = 'long' AND pnl > 0 THEN 1 ELSE 0 END), 0) as longs_gagnants,
                IFNULL(SUM(CASE WHEN sens = 'short' AND pnl > 0 THEN 1 ELSE 0 END), 0) as shorts_gagnants,
                IFNULL(COUNT(*) / NULLIF(TIMESTAMPDIFF(DAY, MIN(date_ouverture), NOW()) / 30.0, 0), 0) as tx_par_mois
            FROM transaction
            WHERE id_portefeuille = :pid AND date_cloture IS NOT NULL

        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['pid' => $portefeuilleId]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère toutes les transactions (ouverte ou fermée) d'un utilisateur.
     *
     * @param int $user_id Identifiant de l'utilisateur.
     * @return array Tableau associatif de transactions.
     */
    public function getTransactionsByUserId($user_id)
    {
        $stmt = $this->pdo->prepare('
        SELECT t.*, c.code AS crypto_code
        FROM transaction t
        JOIN portefeuille p ON t.id_portefeuille = p.id_portefeuille
        JOIN cryptotrans c ON t.id_crypto_trans = c.id_crypto_trans
        WHERE p.id_utilisateur = :user_id
        ORDER BY t.date_ouverture DESC
    ');
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
