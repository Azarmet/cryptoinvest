<?php 
// Inclusion de l’en‑tête du back‑office (logo, navigation, styles)
require_once RACINE . 'app/views/backoffice/headerback.php'; 
?>

<section class="market-section">
    <!-- Titre de la section de gestion du marché de cryptos -->
    <h1 class="section-title">Crypto Market Management</h1>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php
            // Affichage des messages de succès selon l’action effectuée
            switch ($_GET['success']) {
                case '1':
                    echo '✅ Crypto added to the global market.';
                    break;
                case '2':
                    echo '✅ Crypto removed from the global market.';
                    break;
                case '3':
                    echo '✅ Crypto added to transactions.';
                    break;
                case '4':
                    echo '✅ Crypto removed from transactions.';
                    break;
            }
            ?>
        </div>
    <?php endif; ?>

    <!-- Onglets pour basculer entre marché global et liste de transactions -->
    <div class="market-tabs">
        <button class="market-tab-button active" data-tab="global">🔹 Global Market</button>
        <button class="market-tab-button" data-tab="transaction">🔸 Transactions</button>
    </div>

    <!-- Contenu de l’onglet "Global Market" -->
    <div class="market-tab-content active" id="tab-global">
        <h2>🔹 Global Market Cryptos</h2>

        <!-- Formulaire d’ajout de nouvelle crypto au marché global -->
        <form method="POST" action="index.php?pageback=createCryptoMarket" class="crypto-form">
            <input type="text" name="code" placeholder="Code (ex: BTC)" required>

            <label>Categories:</label>
            <div class="checkbox-group">
                <?php
                // Liste des catégories disponibles
                $categories = ["top10", "layer1", "new", "layer2", "web3", "meme", "ai", "defi", "nft"];
                foreach ($categories as $cat): ?>
                    <label>
                        <input type="checkbox" name="categories[]" value="<?= $cat ?>">
                        <?= $cat /* Affichage de chaque catégorie */ ?>
                    </label>
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
                                <!-- Identification de la crypto dans la table -->
                                <td><?= $crypto['id_crypto_market'] ?></td>
                                <!-- Affichage du code de la crypto -->
                                <td><?= htmlspecialchars($crypto['code']) ?></td>
                                <!-- Affichage de la catégorie associée -->
                                <td><?= htmlspecialchars($crypto['categorie']) ?></td>
                                <td>
                                    <!-- Lien de suppression avec confirmation JS -->
                                    <a href="index.php?pageback=deleteCryptoMarket&id=<?= $crypto['id_crypto_market'] ?>"
                                       onclick="return confirm('Delete this crypto?')"
                                       class="action-btn delete">
                                        🗑️
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <!-- Message lorsque aucune crypto n’est présente -->
            <p class="no-user">No cryptocurrency added to the market.</p>
        <?php endif; ?>
    </div>

    <!-- Contenu de l’onglet "Transactions" -->
    <div class="market-tab-content" id="tab-transaction">
        <h2>🔸 Cryptos Available for Transactions</h2>

        <!-- Formulaire d’ajout d’une crypto pour la section transactions -->
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
                                <!-- ID de la crypto transaction -->
                                <td><?= $crypto['id_crypto_trans'] ?></td>
                                <!-- Code de la crypto transaction -->
                                <td><?= htmlspecialchars($crypto['code']) ?></td>
                                <td>
                                    <!-- Lien de suppression avec confirmation JS -->
                                    <a href="index.php?pageback=deleteCryptoTrans&id=<?= $crypto['id_crypto_trans'] ?>"
                                       onclick="return confirm('Delete?')"
                                       class="action-btn delete">
                                        🗑️
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <!-- Message lorsque la liste des cryptos transactionnelles est vide -->
            <p class="no-user">No transactional cryptocurrency added.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Chargement du script spécifique au back‑office du marché -->
<script src="<?= RACINE_URL; ?>public/js/backmarket.js"></script>

<?php 
// Inclusion du pied de page du back‑office (mentions, déconnexion, etc.)
require_once RACINE . 'app/views/backoffice/footerback.php'; 
?>
