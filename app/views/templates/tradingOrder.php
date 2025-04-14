<link rel="stylesheet" href="<?= RACINE_URL . 'public/css/templates/tradingOrder.css'?>">
<!-- SECTION 2 : Trading -->
<section id="trading-section" class="trading-section">
        <h3>Place an Order</h3>
        <p id="available-balance">Available Balance: Loading...</p>
        <?php if (isset($_GET['error']) && $_GET['error'] === 'solde_insuffisant'): ?>
            <p class="error-msg">Insufficient balance to open this position.</p>
        <?php endif; ?>
        <form action="index.php?page=market&action=openPosition" method="POST" class="order-form">
            <div class="form-group">
                <label for="crypto_code">Crypto:</label>
                <select name="crypto_code" id="crypto_code">
                  <?php
                  // $cryptosTrans has been defined in showDashboard()
                  // Create an option for each available code
                  foreach ($cryptosTrans as $code) {
                      echo '<option value="' . htmlspecialchars($code) . '">' . htmlspecialchars($code) . '</option>';
                  }
                  ?>
                </select>
            </div>
            <div class="form-group">
                <label for="montant">Amount (USDT):</label>
                <input type="number" id="montant" name="montant" step="0.01" min="0">
            </div>
            <div class="form-group radio-group">
                <label>
                    <input type="radio" name="type" value="long" checked> Long
                </label>
                <label>
                    <input type="radio" name="type" value="short"> Short
                </label>
            </div>
            <button type="submit" class="submit-btn">Open Position</button>
        </form>
</section>
