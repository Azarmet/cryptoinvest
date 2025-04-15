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
                <!-- Menu personnalisé stylé -->
                <div class="custom-select-wrapper">
                    <div class="custom-select" id="crypto_code_custom">
                        <div class="selected-option">Select a crypto</div>
                        <div class="options-list">
                            <?php foreach ($cryptosTrans as $code): ?>
                            <div class="option" data-value="<?= htmlspecialchars($code) ?>"><?= htmlspecialchars($code) ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Select masqué pour compatibilité JS -->
                <select id="crypto_code" name="crypto_code" style="display: none;">
                <?php foreach ($cryptosTrans as $code): ?>
                    <option value="<?= htmlspecialchars($code) ?>"><?= htmlspecialchars($code) ?></option>
                <?php endforeach; ?>
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
<script src="<?php echo RACINE_URL; ?>public/js/tradingorder.js"></script>
