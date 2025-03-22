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