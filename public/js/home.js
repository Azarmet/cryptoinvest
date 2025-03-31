// Fonction de rafraîchissement du tableau via AJAX
function refreshMarketData(category = "top") {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "index.php?page=market&action=refresh&categorie=" + category, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var cryptos = JSON.parse(xhr.responseText);
            var tbody = document.querySelector("#top10-market");
            var htmlContent = "";
            cryptos.forEach(function(crypto) {
                var variation = parseFloat(crypto.variation_24h).toFixed(2);
                let colorClass = variation >= 0 ? 'positive' : 'negative';
                htmlContent += "<tr>";
                htmlContent += "<td>" + crypto.code + "</td>";
                htmlContent += "<td class='" + colorClass + "'>" + crypto.prix_actuel + "</td>";
                htmlContent += "<td class='" + colorClass + "'>" + variation + "%" + "</td>";
                htmlContent += "</tr>";
            });
            tbody.innerHTML = htmlContent;
        }
    };
    xhr.send();
}
window.onload = function() {
    refreshMarketData();
};
setInterval(function() {
    refreshMarketData();
}, 5000);
