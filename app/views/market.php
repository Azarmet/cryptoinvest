<?php require_once RACINE . "app/views/templates/header.php"; ?>

<h2>Marché des Cryptomonnaies</h2>

<table id="market-table" border="1" cellpadding="5">
    <thead>
        <tr>
            <th>Code</th>
            <th>Prix Actuel</th>
            <th>Variation 24H</th>
            <th>Date Mise à Jour</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cryptos as $crypto): ?>
        <tr>
            <td><?php echo htmlspecialchars($crypto['code']); ?></td>
            <td><?php echo htmlspecialchars($crypto['prix_actuel']); ?></td>
            <td><?php echo htmlspecialchars($crypto['variation_24h']); ?></td>
            <td><?php echo htmlspecialchars($crypto['date_maj']); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
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
                var row = "<tr>" +
                          "<td>" + crypto.code + "</td>" +
                          "<td>" + crypto.prix_actuel + "</td>" +
                          "<td>" + crypto.variation_24h + "</td>" +
                          "<td>" + crypto.date_maj + "</td>" +
                          "</tr>";
                tbody.innerHTML += row;
            });
        }
    };
    xhr.send();
}

// Rafraîchir toutes les 50 secondes (50000 millisecondes)
setInterval(refreshMarketData, 50000);
</script>

<?php require_once RACINE . "app/views/templates/footer.php"; ?>
