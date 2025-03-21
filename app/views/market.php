<?php require_once RACINE . "app/views/templates/header.php"; ?>

<h2>Marché des Cryptomonnaies</h2>

<table id="market-table" border="1" cellpadding="5">
    <thead>
        <tr>
            <th></th>
            <th>Actual Price</th>
            <th>Variation 24H</th>
            <th>Last Update</th>
            <?php if(isset($_SESSION['user'])): ?>
                <th>Watchlist</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cryptos as $crypto): ?>
        <tr>
            <td><?php echo htmlspecialchars($crypto['code']); ?></td>
            <td><?php echo htmlspecialchars($crypto['prix_actuel']); ?></td>
            <td><?php echo number_format($crypto['variation_24h'], 2, '.', '') ."%"; ?></td>
            <td><?php echo htmlspecialchars($crypto['date_maj']); ?></td>
            <?php if(isset($_SESSION['user'])): ?>
                <td>
                    <a href="index.php?page=watchlist&action=add&id=<?php echo $crypto['id_crypto_market']; ?>">
                        Add
                    </a>
                </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
// Définir une variable pour savoir si l'utilisateur est connecté
var isLoggedIn = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;

// Fonction de rafraîchissement du tableau via AJAX
function refreshMarketData() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "index.php?page=market&action=refresh", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var cryptos = JSON.parse(xhr.responseText);
            var tbody = document.querySelector("#market-table tbody");
            tbody.innerHTML = "";
            cryptos.forEach(function(crypto) {
                var variation = parseFloat(crypto.variation_24h).toFixed(2);
                var row = "<tr>" +
                          "<td>" + crypto.code + "</td>" +
                          "<td>" + crypto.prix_actuel + "</td>" +
                          "<td>" + variation +"%" + "</td>" +
                          "<td>" + crypto.date_maj + "</td>";
                if(isLoggedIn) {
                    row += "<td><a href=\"index.php?page=watchlist&action=add&id=" + crypto.id_crypto_market + "\">Add</a></td>";
                }
                row += "</tr>";
                tbody.innerHTML += row;
            });
        }
    };
    xhr.send();
}

// Rafraîchir toutes les 50 secondes (50000 millisecondes)
setInterval(refreshMarketData, 5000);
</script>

<?php require_once RACINE . "app/views/templates/footer.php"; ?>
