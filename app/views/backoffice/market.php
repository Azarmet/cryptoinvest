<?php require_once RACINE . 'app/views/backoffice/headerback.php'; ?>

<section class="market-section">
    <h1 class="section-title">Crypto Market Management</h1>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php
            switch ($_GET['success']) {
                case '1': echo '✅ Crypto added to the global market.'; break;
                case '2': echo '✅ Crypto removed from the global market.'; break;
                case '3': echo '✅ Crypto added to transactions.'; break;
                case '4': echo '✅ Crypto removed from transactions.'; break;
            }
            ?>
        </div>
    <?php endif; ?>

    <!-- Tabs -->
    <div class="market-tabs">
        <button class="market-tab-button active" data-tab="global">🔹 Global Market</button>
        <button class="market-tab-button" data-tab="transaction">🔸 Transactions</button>
    </div>

    <!-- Global Tab Content -->
    <div class="market-tab-content active" id="tab-global">
        <h2>🔹 Global Market Cryptos</h2>

        <form method="POST" action="index.php?pageback=createCryptoMarket" class="crypto-form">
            <input type="text" name="code" placeholder="Code (ex: BTC)" required>

            <label>Categories:</label>
            <div class="checkbox-group">
                <?php
                $categories = ["top10", "layer1", "new", "layer2", "web3", "meme", "ai", "defi", "nft"];
                foreach ($categories as $cat): ?>
                    <label><input type="checkbox" name="categories[]" value="<?= $cat ?>"> <?= $cat ?></label>
                <?php endforeach; ?>
            </div>
            <button type="submit" class="btn-submit">➕ Add</button>
        </form>

        <?php if (!empty($marketCryptos)): ?>
            <div class="table-container">
                <table class="market-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($marketCryptos as $crypto): ?>
                            <tr>
                                <td><?= $crypto['id_crypto_market'] ?></td>
                                <td><?= htmlspecialchars($crypto['code']) ?></td>
                                <td><?= htmlspecialchars($crypto['categorie']) ?></td>
                                <td>
                                    <a href="index.php?pageback=deleteCryptoMarket&id=<?= $crypto['id_crypto_market'] ?>" onclick="return confirm('Delete this crypto?')" class="action-btn delete">🗑️</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="no-user">No cryptocurrency added to the market.</p>
        <?php endif; ?>
    </div>

    <!-- Transaction Tab Content -->
    <div class="market-tab-content" id="tab-transaction">
        <h2>🔸 Cryptos Available for Transactions</h2>

        <form method="POST" action="index.php?pageback=createCryptoTrans" class="crypto-form">
            <input type="text" name="code" placeholder="Code (ex: BTC)" required>
            <button type="submit" class="btn-submit">➕ Add</button>
        </form>

        <?php if (!empty($transCryptos)): ?>
            <div class="table-container">
                <table class="market-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transCryptos as $crypto): ?>
                            <tr>
                                <td><?= $crypto['id_crypto_trans'] ?></td>
                                <td><?= htmlspecialchars($crypto['code']) ?></td>
                                <td>
                                    <a href="index.php?pageback=deleteCryptoTrans&id=<?= $crypto['id_crypto_trans'] ?>" onclick="return confirm('Delete?')" class="action-btn delete">🗑️</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="no-user">No transactional cryptocurrency added.</p>
        <?php endif; ?>
    </div>
</section>

<script src="<?php echo RACINE_URL; ?>public/js/backmarket.js"></script>


<?php require_once RACINE . 'app/views/backoffice/footerback.php'; ?>
