<?php
namespace App\Models;

use PDO;
use App\Models\Database;

class Portefeuille {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    /**
     * Calcule le solde disponible = capital_initial + somme des PnL (closed) - somme investie dans positions open.
     */
    public function getSoldeDisponible($userId) {
        // 1) Récupérer le portefeuille de l'utilisateur
        $sql = "SELECT id_portefeuille, capital_initial
                FROM portefeuille
                WHERE id_utilisateur = :userId
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        $pf = $stmt->fetch();
        if (!$pf) {
            return 0;
        }
        $capitalInitial = floatval($pf['capital_initial']);
        $pfId = $pf['id_portefeuille'];

        // 2) Calculer la somme investie dans les positions open
        $sqlOpen = "SELECT SUM(prix_ouverture * quantite) as sommeEngagee
                    FROM transaction
                    WHERE statut = 'open'
                      AND id_portefeuille = :pfId";
        $stmt2 = $this->pdo->prepare($sqlOpen);
        $stmt2->execute([':pfId' => $pfId]);
        $row2 = $stmt2->fetch();
        $sommeEngagee = floatval($row2['sommeEngagee']);

        // 3) Calculer les gains/pertes réalisés (transactions close)
        $sqlClose = "SELECT SUM(pnl) as sommePnl
                     FROM transaction
                     WHERE statut = 'close'
                       AND id_portefeuille = :pfId";
        $stmt3 = $this->pdo->prepare($sqlClose);
        $stmt3->execute([':pfId' => $pfId]);
        $row3 = $stmt3->fetch();
        $sommePnl = floatval($row3['sommePnl']);

        // 4) Calcul du solde disponible
        $solde = $capitalInitial + $sommePnl - $sommeEngagee;
        return $solde;
    }


    public function getSoldeActuel($userId) {
        $sql = "SELECT capital_actuel FROM portefeuille WHERE id_utilisateur = :userId LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        $row = $stmt->fetch();
        return $row ? floatval($row['capital_actuel']) : 0;
    }
    

    /**
     * Retourne l'historique du solde pour le graphique.
     */
    public function getSoldeHistory($userId, $interval) {
        // Récupérer le portefeuille de l'utilisateur
        $sql = "SELECT id_portefeuille, capital_initial FROM portefeuille WHERE id_utilisateur = :userId LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        $pf = $stmt->fetch();
        if (!$pf) {
            return [];
        }
        $capitalInitial = floatval($pf['capital_initial']);
        $pfId = $pf['id_portefeuille'];
    
        // Définir la date de début en fonction de l'intervalle choisi
        $now = new \DateTime();
        switch ($interval) {
            case 'jour':
                $startDate = clone $now;
                $startDate->modify('-24 hours');
                break;
            case 'semaine':
                $startDate = clone $now;
                $startDate->modify('-7 days');
                break;
            case 'mois':
                $startDate = clone $now;
                $startDate->modify('-30 days');
                break;
            case 'annee':
                $startDate = clone $now;
                $startDate->modify('-365 days');
                break;
            default:
                // Si l'intervalle est invalide, on renvoie l'historique complet
                $startDate = new \DateTime('1970-01-01');
                break;
        }
        $startDateStr = $startDate->format('Y-m-d H:i:s');
    
        // Calculer le solde au début de la période : capital_initial + PnL des transactions avant startDate
        $sqlBefore = "SELECT SUM(pnl) as totalPnl
                      FROM transaction
                      WHERE statut = 'close'
                        AND id_portefeuille = :pfId
                        AND date_cloture < :startDate";
        $stmtBefore = $this->pdo->prepare($sqlBefore);
        $stmtBefore->execute([':pfId' => $pfId, ':startDate' => $startDateStr]);
        $rowBefore = $stmtBefore->fetch();
        $pnlBefore = floatval($rowBefore['totalPnl']);
        $balance = $capitalInitial + $pnlBefore;
    
        // Point initial de l'historique : début de période avec le solde calculé
        $history = [];
        $history[] = ['date' => $startDateStr, 'solde' => $balance];
    
        // Récupérer toutes les transactions clôturées depuis le début de la période, triées par date_cloture
        $sqlTx = "SELECT date_cloture, pnl 
                  FROM transaction 
                  WHERE statut = 'close'
                    AND id_portefeuille = :pfId
                    AND date_cloture >= :startDate
                  ORDER BY date_cloture ASC";
        $stmtTx = $this->pdo->prepare($sqlTx);
        $stmtTx->execute([':pfId' => $pfId, ':startDate' => $startDateStr]);
        $transactions = $stmtTx->fetchAll(PDO::FETCH_ASSOC);
    
        // Pour chaque transaction, mettre à jour le solde et ajouter un point
        foreach ($transactions as $tx) {
            $balance += floatval($tx['pnl']);
            $history[] = ['date' => $tx['date_cloture'], 'solde' => $balance];
        }
    
        // Ajouter enfin le point actuel (même si aucune transaction n'est intervenue après le début de la période)
        $nowStr = $now->format('Y-m-d H:i:s');
        $history[] = ['date' => $nowStr, 'solde' => $balance];
    
        return $history;
    }
    
    

    /**
     * Renvoie les statistiques globales du portefeuille : ROI total, PnL total, nombre de transactions.
     */
    public function getPortfolioStats($userId) {
        $sql = "SELECT id_portefeuille, capital_initial
                FROM portefeuille
                WHERE id_utilisateur = :userId
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        $pf = $stmt->fetch();
        if (!$pf) {
            return [
                'roiTotal' => 0,
                'pnlTotal' => 0,
                'txCount'  => 0
            ];
        }
        $capitalInitial = floatval($pf['capital_initial']);
        $pfId = $pf['id_portefeuille'];

        $sqlPnl = "SELECT SUM(pnl) as totalPnl
                   FROM transaction
                   WHERE statut = 'close'
                     AND id_portefeuille = :pfId";
        $stmtPnl = $this->pdo->prepare($sqlPnl);
        $stmtPnl->execute([':pfId' => $pfId]);
        $rowPnl = $stmtPnl->fetch();
        $pnlTotal = floatval($rowPnl['totalPnl']);

        $sqlCount = "SELECT COUNT(*) as nbTx
                     FROM transaction
                     WHERE id_portefeuille = :pfId";
        $stmtCount = $this->pdo->prepare($sqlCount);
        $stmtCount->execute([':pfId' => $pfId]);
        $rowCount = $stmtCount->fetch();
        $txCount = intval($rowCount['nbTx']);

        $roiTotal = ($capitalInitial > 0) ? (($pnlTotal / $capitalInitial) * 100) : 0;

        return [
            'roiTotal' => round($roiTotal, 2),
            'pnlTotal' => round($pnlTotal, 2),
            'txCount'  => $txCount
        ];
    }
}
