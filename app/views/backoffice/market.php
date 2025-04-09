<?php require_once RACINE . 'app/views/backoffice/headerback.php'; ?>

<section class="market-section">
    <h1 class="section-title">Gestion du Marché Crypto</h1>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php
            switch ($_GET['success']) {
                case '1': echo '✅ Crypto ajoutée au marché global.'; break;
                case '2': echo '✅ Crypto supprimée du marché global.'; break;
                case '3': echo '✅ Crypto ajoutée aux transactions.'; break;
                case '4': echo '✅ Crypto supprimée des transactions.'; break;
            }
            ?>
        </div>
    <?php endif; ?>

    <!-- Onglets -->
    <div class="market-tabs">
        <button class="market-tab-button active" data-tab="global">🔹 Marché Global</button>
        <button class="market-tab-button" data-tab="transaction">🔸 Transactions</button>
    </div>

    <!-- Contenu Onglet Global -->
    <div class="market-tab-content active" id="tab-global">
        <h2>🔹 Cryptos du Marché Global</h2>

        <form method="POST" action="index.php?pageback=createCryptoMarket" class="crypto-form">
            <input type="text" name="code" placeholder="Code (ex: BTC)" required>

            <label>Catégories :</label>
            <div class="checkbox-group">
                <?php
                $categories = ["top10", "layer1", "new", "layer2", "web3", "meme", "ai", "defi", "nft"];
                foreach ($categories as $cat): ?>
                    <label><input type="checkbox" name="categories[]" value="<?= $cat ?>"> <?= $cat ?></label>
                <?php endforeach; ?>
            </div>
            <button type="submit" class="btn-submit">➕ Ajouter</button>
        </form>

        <?php if (!empty($marketCryptos)): ?>
            <div class="table-container">
                <table class="market-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Catégorie</th>
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
                                    <a href="index.php?pageback=deleteCryptoMarket&id=<?= $crypto['id_crypto_market'] ?>" onclick="return confirm('Supprimer cette crypto ?')" class="action-btn delete">🗑️</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="no-user">Aucune cryptomonnaie ajoutée au marché.</p>
        <?php endif; ?>
    </div>

    <!-- Contenu Onglet Transaction -->
    <div class="market-tab-content" id="tab-transaction">
        <h2>🔸 Cryptos disponibles pour les Transactions</h2>

        <form method="POST" action="index.php?pageback=createCryptoTrans" class="crypto-form">
            <input type="text" name="code" placeholder="Code (ex: BTC)" required>
            <button type="submit" class="btn-submit">➕ Ajouter</button>
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
                                    <a href="index.php?pageback=deleteCryptoTrans&id=<?= $crypto['id_crypto_trans'] ?>" onclick="return confirm('Supprimer ?')" class="action-btn delete">🗑️</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="no-user">Aucune cryptomonnaie transactionnelle ajoutée.</p>
        <?php endif; ?>
    </div>
</section>

<script>
    document.querySelectorAll('.market-tab-button').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.market-tab-button').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.market-tab-content').forEach(tab => tab.classList.remove('active'));

            btn.classList.add('active');
            document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
        });
    });
</script>

<?php require_once RACINE . 'app/views/backoffice/footerback.php'; ?>
