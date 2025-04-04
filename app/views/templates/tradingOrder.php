<link rel="stylesheet" href="<?= RACINE_URL . 'public/css/tradingOrder.css'?>">
<!-- SECTION 2 : Trading -->
<section id="trading-section" class="trading-section">
        <h3>Passer un ordre</h3>
        <p id="available-balance">Solde disponible : Loading...</p>
        <?php if (isset($_GET['error']) && $_GET['error'] === 'solde_insuffisant'): ?>
            <p class="error-msg">Solde insuffisant pour ouvrir cette position.</p>
        <?php endif; ?>
        <form action="index.php?page=market&action=openPosition" method="POST" class="order-form">
            <div class="form-group">
                <label for="crypto_code">Crypto :</label>
                <select name="crypto_code" id="crypto_code">
                  <?php
                  // $cryptos a été défini dans showDashboard()
                  // On crée une option pour chaque code disponible
                  foreach ($cryptosTrans as $code) {
                      echo '<option value="' . htmlspecialchars($code) . '">' . htmlspecialchars($code) . '</option>';
                  }
                  ?>
                </select>
            </div>
            <div class="form-group">
                <label for="montant">Montant (USDT) :</label>
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
            <button type="submit" class="submit-btn">Ouvrir une position</button>
        </form>
    </section>