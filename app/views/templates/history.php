
<!-- Transaction History -->
<div class="transactions-section">
  <h2>Transaction History</h2>
  <div class="table-responsive-market">
    <table id="transactionsTable" class="market-table transaction-table">
      <thead>
        <tr>
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
            <td><?= htmlspecialchars($transaction['date_ouverture']) ?></td>
            <td><?= htmlspecialchars($transaction['crypto_code']) ?></td>
            <td><?= strtoupper($transaction['sens']) ?></td>
            <td><?= $transaction['quantite'] ?></td>
            <td><?= number_format($transaction['prix_ouverture'], 4) ?> $</td>
            <td><?= $transaction['prix_cloture'] !== null ? number_format($transaction['prix_cloture'], 4) . ' $' : '-' ?></td>
            <td style="color: <?= $transaction['pnl'] > 0 ? '#2ecc71' : ($transaction['pnl'] < 0 ? '#e74c3c' : '#fff') ?>">
              <?= $transaction['pnl'] !== null ? number_format($transaction['pnl'], 2) . ' $' : '-' ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<script src="<?php echo RACINE_URL; ?>public/js/history.js"></script>


