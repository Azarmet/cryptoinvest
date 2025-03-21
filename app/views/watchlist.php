<?php require_once RACINE . "app/views/templates/header.php"; ?>

<h2>Ma Watchlist</h2>

<table id="watchlist-table" border="1" cellpadding="5">
    <thead>
        <tr>
            <th>Code</th>
            <th>Prix Actuel</th>
            <th>Variation 24H</th>
            <th>Date Mise à Jour</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($cryptos)): ?>
            <?php foreach ($cryptos as $crypto): ?>
                <tr>
                    <td><?php echo htmlspecialchars($crypto['code']); ?></td>
                    <td><?php echo htmlspecialchars($crypto['prix_actuel']); ?></td>
                    <td><?php echo htmlspecialchars($crypto['variation_24h']); ?></td>
                    <td><?php echo htmlspecialchars($crypto['date_maj']); ?></td>
                    <td>
                        <a href="index.php?page=watchlist&action=remove&id=<?php echo $crypto['id_crypto_market']; ?>">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">Votre watchlist est vide.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
// Fonction de rafraîchissement du tableau via AJAX
function refreshWatchlistData() {
    var xhr = new XMLHttpRequest();
    // Ajout d'un paramètre "timestamp" pour éviter la mise en cache
    xhr.open("GET", "index.php?page=watchlist&action=refresh&timestamp=" + new Date().getTime(), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var cryptos = JSON.parse(xhr.responseText);
            var tbody = document.querySelector("#watchlist-table tbody");
            tbody.innerHTML = "";
            if (cryptos.length > 0) {
                cryptos.forEach(function(crypto) {
                    var row = "<tr>" +
                              "<td>" + crypto.code + "</td>" +
                              "<td>" + crypto.prix_actuel + "</td>" +
                              "<td>" + crypto.variation_24h + "</td>" +
                              "<td>" + crypto.date_maj + "</td>" +
                              "<td><a href='index.php?page=watchlist&action=remove&id=" + crypto.id_crypto_market + "'>Supprimer</a></td>" +
                              "</tr>";
                    tbody.innerHTML += row;
                });
            } else {
                tbody.innerHTML = "<tr><td colspan='5'>Votre watchlist est vide.</td></tr>";
            }
        }
    };
    xhr.send();
}


// Rafraîchir la watchlist toutes les 50 secondes (50000 millisecondes)
setInterval(refreshWatchlistData, 50000);
</script>

<?php require_once RACINE . "app/views/templates/footer.php"; ?>
