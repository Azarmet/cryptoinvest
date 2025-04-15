
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

<script>
document.addEventListener('DOMContentLoaded', () => {
  const table = document.getElementById('transactionsTable');
  const headers = table.querySelectorAll('th.sortable');

  headers.forEach((header, index) => {
    header.style.cursor = 'pointer';

    header.addEventListener('click', () => {
      const type = header.getAttribute('data-type');
      const rows = Array.from(table.querySelectorAll('tbody tr'));

      // Toggle sort direction (asc / desc)
      const isAscending = !header.classList.contains('asc');

      // Reset all sort classes
      headers.forEach(h => h.classList.remove('asc', 'desc'));
      header.classList.add(isAscending ? 'asc' : 'desc');

      // Function to clean numbers (remove commas, €, $, etc.)
      const cleanNumber = text =>
        parseFloat(text.replace(/[^\d.-]/g, '').replace(',', ''));

      const sortedRows = rows.sort((a, b) => {
        const aText = a.children[index].innerText.trim();
        const bText = b.children[index].innerText.trim();

        let aValue = aText;
        let bValue = bText;

        if (type === 'number') {
          aValue = cleanNumber(aText);
          bValue = cleanNumber(bText);
        } else if (type === 'date') {
          aValue = new Date(aText);
          bValue = new Date(bText);
        }

        if (aValue < bValue) return isAscending ? -1 : 1;
        if (aValue > bValue) return isAscending ? 1 : -1;
        return 0;
      });

      const tbody = table.querySelector('tbody');
      tbody.innerHTML = '';
      sortedRows.forEach(row => tbody.appendChild(row));
    });
  });
});
</script>
