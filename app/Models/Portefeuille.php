<?php
namespace App\Models;

use App\Models\Database;
use PDO;

/**
 * Classe de gestion du portefeuille d'un utilisateur et calculs financiers associés.
 */
class Portefeuille
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    /**
     * Calcule le solde disponible pour un utilisateur :
     * capital_initial + somme des PnL réalisés – somme engagée dans les positions ouvertes.
     *
     * @param int $userId Identifiant de l'utilisateur.
     * @return float Solde disponible, arrondi à deux décimales.
     */
    public function getSoldeDisponible($userId)
    {
        $sql = 'SELECT id_portefeuille, capital_initial
            FROM portefeuille
            WHERE id_utilisateur = :userId
            LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        $pf = $stmt->fetch();

        if (!$pf) {
            return 0.0;
        }

        $capitalInitial = floatval($pf['capital_initial']);
        $pfId = (int) $pf['id_portefeuille'];

        // Somme des positions ouvertes
        $sqlOpen = "SELECT SUM(prix_ouverture * quantite) as sommeEngagee
                FROM transaction
                WHERE statut = 'open'
                  AND id_portefeuille = :pfId";
        $stmt2 = $this->pdo->prepare($sqlOpen);
        $stmt2->execute([':pfId' => $pfId]);
        $sommeEngagee = floatval($stmt2->fetchColumn() ?? 0);

        // Somme des PnL réalisés
        $sqlClose = "SELECT SUM(pnl) as sommePnl
                 FROM transaction
                 WHERE statut = 'close'
                   AND id_portefeuille = :pfId";
        $stmt3 = $this->pdo->prepare($sqlClose);
        $stmt3->execute([':pfId' => $pfId]);
        $sommePnl = floatval($stmt3->fetchColumn() ?? 0);

        $solde = $capitalInitial + $sommePnl - $sommeEngagee;
        return round($solde, 2);
    }


    /**
     * Récupère le solde actuel enregistré en base pour l'utilisateur.
     *
     * @param int $userId Identifiant de l'utilisateur.
     * @return float Solde actuel ou 0.0 si non trouvé.
     */
    public function getSoldeActuel($userId): float
    {
        $sql = 'SELECT capital_actuel FROM portefeuille WHERE id_utilisateur = :userId LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return isset($row['capital_actuel']) ? floatval($row['capital_actuel']) : 0.0;
    }

    /**
     * Fournit l'historique du solde sur un intervalle donné pour graphique.
     *
     * @param int    $userId   Identifiant de l'utilisateur.
     * @param string $interval Intervalle : 'jour', 'semaine', 'mois' ou 'annee'.
     * @return array Tableau d'entrées ['date' => string, 'solde' => float].
     */
    public function getSoldeHistory($userId, $interval): array
    {
        // Étape 1 : Récupération du portefeuille
        $sql = 'SELECT id_portefeuille, capital_initial FROM portefeuille WHERE id_utilisateur = :userId LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        $pf = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pf)
            return [];

        $capitalInitial = floatval($pf['capital_initial']);
        $pfId = (int) $pf['id_portefeuille'];

        // Étape 2 : Gestion sécurisée des intervalles
        $now = new \DateTime();
        $intervals = [
            'jour' => '-24 hours',
            'semaine' => '-7 days',
            'mois' => '-30 days',
            'annee' => '-365 days'
        ];

        $startDate = isset($intervals[$interval])
            ? $now->modify($intervals[$interval])
            : new \DateTime('1970-01-01');

        $startDateStr = $startDate->format('Y-m-d H:i:s');

        // Étape 3 : Calcul du solde initial à cette date
        $sqlBefore = "SELECT SUM(pnl) as totalPnl FROM transaction
                  WHERE statut = 'close' AND id_portefeuille = :pfId AND date_cloture < :startDate";
        $stmtBefore = $this->pdo->prepare($sqlBefore);
        $stmtBefore->execute([':pfId' => $pfId, ':startDate' => $startDateStr]);
        $rowBefore = $stmtBefore->fetch(PDO::FETCH_ASSOC);
        $pnlBefore = isset($rowBefore['totalPnl']) ? floatval($rowBefore['totalPnl']) : 0.0;

        $balance = $capitalInitial + $pnlBefore;

        // Initialiser l'historique
        $history = [['date' => $startDateStr, 'solde' => $balance]];

        // Étape 4 : Transactions clôturées depuis cette date
        $sqlTx = "SELECT date_cloture, pnl FROM transaction
              WHERE statut = 'close' AND id_portefeuille = :pfId AND date_cloture >= :startDate
              ORDER BY date_cloture ASC";
        $stmtTx = $this->pdo->prepare($sqlTx);
        $stmtTx->execute([':pfId' => $pfId, ':startDate' => $startDateStr]);
        $transactions = $stmtTx->fetchAll(PDO::FETCH_ASSOC);

        foreach ($transactions as $tx) {
            $balance += floatval($tx['pnl']);
            $history[] = [
                'date' => $tx['date_cloture'],
                'solde' => round($balance, 2)
            ];
        }

        // Ajouter le point final (maintenant)
        $history[] = [
            'date' => (new \DateTime())->format('Y-m-d H:i:s'),
            'solde' => round($balance, 2)
        ];

        return $history;
    }

    /**
     * Renvoie les statistiques globales du portefeuille :
     * ROI total (%), PnL total, et nombre de transactions.
     *
     * @param int $userId Identifiant de l'utilisateur.
     * @return array ['roiTotal' => float, 'pnlTotal' => float, 'txCount' => int].
     */
    public function getPortfolioStats($userId): array
    {
        // 1. Récupération du portefeuille
        $sql = 'SELECT id_portefeuille, capital_initial FROM portefeuille WHERE id_utilisateur = :userId LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        $pf = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pf) {
            return [
                'roiTotal' => 0.0,
                'pnlTotal' => 0.0,
                'txCount' => 0
            ];
        }

        $capitalInitial = floatval($pf['capital_initial']);
        $pfId = (int) $pf['id_portefeuille'];

        // 2. Total PnL
        $sqlPnl = 'SELECT SUM(pnl) AS totalPnl FROM transaction WHERE statut = "close" AND id_portefeuille = :pfId';
        $stmtPnl = $this->pdo->prepare($sqlPnl);
        $stmtPnl->execute([':pfId' => $pfId]);
        $pnlRow = $stmtPnl->fetch(PDO::FETCH_ASSOC);
        $pnlTotal = isset($pnlRow['totalPnl']) ? floatval($pnlRow['totalPnl']) : 0.0;

        // 3. Total transactions
        $sqlCount = 'SELECT COUNT(*) AS nbTx FROM transaction WHERE id_portefeuille = :pfId';
        $stmtCount = $this->pdo->prepare($sqlCount);
        $stmtCount->execute([':pfId' => $pfId]);
        $countRow = $stmtCount->fetch(PDO::FETCH_ASSOC);
        $txCount = isset($countRow['nbTx']) ? (int) $countRow['nbTx'] : 0;

        // 4. Calcul ROI
        $roiTotal = ($capitalInitial > 0) ? ($pnlTotal / $capitalInitial) * 100 : 0;

        return [
            'roiTotal' => round($roiTotal, 2),
            'pnlTotal' => round($pnlTotal, 2),
            'txCount' => $txCount
        ];
    }


    /**
     * Calcule le PnL réalisé sur les X derniers jours.
     *
     * @param int $userId   Identifiant de l'utilisateur.
     * @param int $nb_jour  Nombre de jours (1 à 365).
     * @return float Montant total du PnL, ou 0.0 si aucune donnée.
     */
    public function getPnL(int $userId, int $nb_jour = 1): float
    {
        // Validation simple
        if ($nb_jour <= 0 || $nb_jour > 365) {
            $nb_jour = 1;
        }

        // 1. Récupérer le portefeuille de l'utilisateur
        $stmt = $this->pdo->prepare('SELECT id_portefeuille FROM portefeuille WHERE id_utilisateur = :userId LIMIT 1');
        $stmt->execute([':userId' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return 0.0;
        }

        $pfId = (int) $row['id_portefeuille'];

        // 2. Calcul du PnL sur les X derniers jours — avec date calculée en PHP (évite le paramètre non autorisé)
        $dateDebut = (new \DateTime())->modify("-{$nb_jour} days")->format('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare("
        SELECT SUM(pnl) as total_pnl
        FROM transaction
        WHERE statut = 'close'
          AND id_portefeuille = :pfId
          AND date_cloture >= :startDate
    ");
        $stmt->execute([
            ':pfId' => $pfId,
            ':startDate' => $dateDebut
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return isset($result['total_pnl']) ? floatval($result['total_pnl']) : 0.0;
    }
}
