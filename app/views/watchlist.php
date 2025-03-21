<?php require_once RACINE . "app/views/templates/header.php"; ?>

<h2>Ma Watchlist</h2>

<table id="watchlist-table" border="1" cellpadding="5">
    <thead>
        <tr>
            <th></th>
            <th>Actual Price</th>
            <th>Variation 24H</th>
            <th>Last Update</th>
            <th>Watchlist</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($cryptos)): ?>
            <?php foreach ($cryptos as $crypto): ?>
                <tr>
                    <td><?php echo htmlspecialchars($crypto['code']); ?></td>
                    <td><?php echo htmlspecialchars($crypto['prix_actuel']); ?></td>
                    <td><?php echo number_format($crypto['variation_24h'], 2, '.', '') ."%"; ?></td>
                    <td><?php echo htmlspecialchars($crypto['date_maj']); ?></td>
                    <td>
                        <a href="index.php?page=watchlist&action=remove&id=<?php echo $crypto['id_crypto_market']; ?>">Remove</a>
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
<a href="index.php?page=market">Go to Market</a>
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
                    var variation = parseFloat(crypto.variation_24h).toFixed(2);
                    var row = "<tr>" +
                              "<td>" + crypto.code + "</td>" +
                              "<td>" + crypto.prix_actuel + "</td>" +
                              "<td>" + variation +"%" + "</td>" +
                              "<td>" + crypto.date_maj + "</td>" +
                              "<td><a href='index.php?page=watchlist&action=remove&id=" + crypto.id_crypto_market + "'>Remove</a></td>" +
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

// Rafraîchir la watchlist toutes les 5 secondes (5000 millisecondes)
setInterval(refreshWatchlistData, 5000);
</script>

<?php require_once RACINE . "app/views/templates/footer.php"; ?>
