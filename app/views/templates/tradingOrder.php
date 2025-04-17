<!-- SECTION 2 : Passer un ordre de trading -->
<section id="trading-section" class="trading-section">
    <!-- Titre de la section -->
    <h3>Place an Order</h3>
    <!-- Affichage du solde disponible, mis à jour en JavaScript -->
    <p id="available-balance">Available Balance: Loading...</p>
    <!-- Affichage d'un message d'erreur si le solde est insuffisant -->
    <?php if (isset($_GET['error']) && $_GET['error'] === 'solde_insuffisant'): ?>
        <p class="error-msg">Insufficient balance to open this position.</p>
    <?php endif; ?>

    <!-- Formulaire de création d'une position (long/short) -->
    <form action="index.php?page=market&action=openPosition" method="POST" class="order-form">
        <div class="form-group">
            <label for="crypto_code">Crypto:</label>
            <!-- Menu personnalisé pour sélectionner le code de la crypto -->
            <div class="custom-select-wrapper">
                <div class="custom-select" id="crypto_code_custom">
                    <!-- Option sélectionnée affichée -->
                    <div class="selected-option">Select a crypto</div>
                    <!-- Liste des options disponibles -->
                    <div class="options-list">
                        <?php foreach ($cryptosTrans as $code): ?>
                        <div class="option" data-value="<?= htmlspecialchars($code) ?>">
                            <?= htmlspecialchars($code) ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Select masqué pour garantir la compatibilité avec le formulaire JS -->
            <select id="crypto_code" name="crypto_code" style="display: none;">
                <?php foreach ($cryptosTrans as $code): ?>
                <option value="<?= htmlspecialchars($code) ?>"><?= htmlspecialchars($code) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Saisie du montant à engager (en USDT) -->
        <div class="form-group">
            <label for="montant">Amount (USDT):</label>
            <input type="number" id="montant" name="montant" step="0.01" min="0">
        </div>

        <!-- Choix du type de position : long ou short -->
        <div class="form-group radio-group">
            <label>
                <input type="radio" name="type" value="long" checked> Long
            </label>
            <label>
                <input type="radio" name="type" value="short"> Short
            </label>
        </div>

        <!-- Bouton de soumission du formulaire -->
        <button type="submit" class="submit-btn">Open Position</button>
    </form>
</section>

<!-- Inclusion du script gérant le fonctionnement du menu personnalisé et du formulaire -->
<script src="<?php echo RACINE_URL; ?>public/js/tradingorder.js"></script>
