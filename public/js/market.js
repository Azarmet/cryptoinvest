// Fonction de rafraîchissement du tableau via AJAX
function refreshMarketData() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "index.php?page=market&action=refresh", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var cryptos = JSON.parse(xhr.responseText);
            var tbody = document.querySelector("#market-table tbody");
            var htmlContent = "";
            cryptos.forEach(function(crypto) {
                var variation = parseFloat(crypto.variation_24h).toFixed(2);
                htmlContent += "<tr>";
                htmlContent += "<td>" + crypto.code + "</td>";
                htmlContent += "<td>" + crypto.prix_actuel + "</td>";
                htmlContent += "<td>" + variation + "%" + "</td>";
                htmlContent += "<td>" + crypto.date_maj + "</td>";
                if (isLoggedIn) {
                    htmlContent += "<td><a href=\"index.php?page=watchlist&action=add&id=" + crypto.id_crypto_market + "\">Add</a></td>";
                }
                htmlContent += "</tr>";
            });
            tbody.innerHTML = htmlContent;
        }
    };
    xhr.send();
}


// Rafraîchir toutes les 50 secondes (50000 millisecondes)
setInterval(refreshMarketData, 5000);