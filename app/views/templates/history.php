<!-- SECTION : Historique des transactions -->
<div class="transactions-section">
  <!-- Titre de la section -->
  <h2>Transaction History</h2>
  
  <!-- Conteneur pour assurer la responsivité du tableau -->
  <div class="table-responsive-market">
    <table id="transactionsTable" class="market-table transaction-table">
      <thead>
        <tr>
          <!-- Colonnes triables, avec type pour le script de tri -->
          <th class="sortable" data-type="date">Date</th>
          <th class="sortable" data-type="text">Code</th>
          <th class="sortable" data-type="text">Type</th>
          <th class="sortable" data-type="number">Quantity</th>
          <th class="sortable" data-type="number">Opening Price</th>
          <th class="sortable" data-type="number">Closing Price</th>
          <th class="sortable" data-type="number">PNL</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($transactions as $transaction): ?>
          <tr>
            <!-- Date d'ouverture de la transaction -->
            <td><?= htmlspecialchars($transaction['date_ouverture']) ?></td>
            <!-- Code de la cryptomonnaie -->
            <td><?= htmlspecialchars($transaction['crypto_code']) ?></td>
            <!-- Sens de la position (LONG/SHORT) en majuscules -->
            <td><?= strtoupper($transaction['sens']) ?></td>
            <!-- Quantité de crypto échangée -->
            <td><?= $transaction['quantite'] ?></td>
            <!-- Prix d'ouverture formaté à 4 décimales -->
            <td><?= number_format($transaction['prix_ouverture'], 4) ?> $</td>
            <!-- Prix de clôture, ou tiret si non clôturé -->
            <td>
              <?= $transaction['prix_cloture'] !== null 
                    ? number_format($transaction['prix_cloture'], 4) . ' $' 
                    : '-' ?>
            </td>
            <!-- PnL formaté et coloré selon signe (vert positif, rouge négatif, blanc neutre) -->
            <td style="color: <?= $transaction['pnl'] > 0 
                              ? '#2ecc71' 
                              : ($transaction['pnl'] < 0 
                                  ? '#e74c3c' 
                                  : '#fff') ?>">
              <?= $transaction['pnl'] !== null 
                    ? number_format($transaction['pnl'], 2) . ' $' 
                    : '-' ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Chargement du script de gestion de l'historique (tri, etc.) -->
<script src="<?php echo RACINE_URL; ?>public/js/history.js"></script>
