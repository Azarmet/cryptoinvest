<?php require_once RACINE . 'app/views/backoffice/headerback.php'; ?>

<h1>Gestion du Marché Crypto</h1>

<?php if (isset($_GET['success'])): ?>
    <div style="background-color: #d4edda; color: #155724; padding: 10px; margin: 15px 0; border-radius: 5px;">
        <?php
        switch ($_GET['success']) {
            case '1':
                echo '✅ Crypto ajoutée au marché global.';
                break;
            case '2':
                echo '✅ Crypto supprimée du marché global.';
                break;
            case '3':
                echo '✅ Crypto ajoutée aux transactions.';
                break;
            case '4':
                echo '✅ Crypto supprimée des transactions.';
                break;
        }
        ?>
    </div>
<?php endif; ?>

<!-- 🔹 Marché global -->
<section style="margin-bottom: 40px;">
    <h2>🔹 Cryptos du Marché Global</h2>

    <form method="POST" action="index.php?pageback=createCryptoMarket">
        <input type="text" name="code" placeholder="Code (ex: BTC)" required>
        <input type="text" name="categorie" placeholder="Catégorie (ex: DeFi, Layer 1...)" required>
        <button type="submit">➕ Ajouter</button>
    </form>

    <?php if (!empty($marketCryptos)): ?>
        <table border="1" cellpadding="10" cellspacing="0" style="margin-top: 20px; width: 100%;">
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
                            <a href="index.php?pageback=deleteCryptoMarket&id=<?= $crypto['id_crypto_market'] ?>" onclick="return confirm('Supprimer cette crypto du marché ?')">🗑️ Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune cryptomonnaie ajoutée au marché.</p>
    <?php endif; ?>
</section>

<!-- 🔸 Cryptos transactionnelles -->
<section>
    <h2>🔸 Cryptos disponibles pour les Transactions</h2>

    <form method="POST" action="index.php?pageback=createCryptoTrans">
        <input type="text" name="code" placeholder="Code (ex: BTC)" required>
        <button type="submit">➕ Ajouter</button>
    </form>

    <?php if (!empty($transCryptos)): ?>
        <table border="1" cellpadding="10" cellspacing="0" style="margin-top: 20px; width: 100%;">
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
                            <a href="index.php?pageback=deleteCryptoTrans&id=<?= $crypto['id_crypto_trans'] ?>" onclick="return confirm('Supprimer cette crypto des transactions ?')">🗑️ Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune cryptomonnaie transactionnelle ajoutée.</p>
    <?php endif; ?>
</section>

<?php require_once RACINE . 'app/views/backoffice/footerback.php'; ?>
