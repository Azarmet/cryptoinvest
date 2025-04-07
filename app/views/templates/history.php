<link rel="stylesheet" href="<?= RACINE_URL . 'public/css/templates/transactions.css'?>">

<!-- Historique des transactions -->
<div class="transactions-section">
  <h2>Historique des Transactions</h2>
  <div class="table-responsive-market">
    <table class="market-table transaction-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Code</th>
          <th>Type</th>
          <th>Quantité</th>
          <th>Prix ouverture</th>
          <th>Prix clôture</th>
          <th>PNL</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($transactions as $transaction): ?>
          <tr>
            <td><?= htmlspecialchars($transaction['date_ouverture']) ?></td>
            <td><?= htmlspecialchars($transaction['crypto_code']) ?></td>
            <td><?= strtoupper($transaction['sens']) ?></td>
            <td><?= $transaction['quantite'] ?></td>
            <td><?= number_format($transaction['prix_ouverture'], 4) ?> €</td>
            <td><?= $transaction['prix_cloture'] !== null ? number_format($transaction['prix_cloture'], 4) . ' €' : '-' ?></td>
            <td style="color: <?= $transaction['pnl'] > 0 ? '#2ecc71' : ($transaction['pnl'] < 0 ? '#e74c3c' : '#fff') ?>">
              <?= $transaction['pnl'] !== null ? number_format($transaction['pnl'], 2) . ' €' : '-' ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
