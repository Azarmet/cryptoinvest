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
                    let colorClass = variation >= 0 ? 'positive' : 'negative';
                    var row = "<tr class='crypto-link'  data-symbol='" + crypto.code + "'>" +
                              "<td><a href='#'>" + crypto.code + "</a></td>"+
                              "<td class='" + colorClass + "'>" + crypto.prix_actuel + "</td>" +
                              "<td class='" + colorClass + "'>" + variation + "%" + "</td>" +
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

function updateTradingViewSymbol(symbol) {
    const container = document.querySelector("#tradingview-widget-container");
    container.innerHTML = ""; // Nettoyer l'ancien widget

    const widgetDiv = document.createElement("div");
    widgetDiv.id = "tradingview_abcdef";
    container.appendChild(widgetDiv);

    const script = document.createElement("script");
    script.type = "text/javascript";
    script.src = "https://s3.tradingview.com/tv.js";
    script.onload = function () {
        new TradingView.widget({
            "container_id": "tradingview_abcdef",
            "symbol": symbol,
            "interval": "D",
            "theme": "dark",
            "style": "1",
            "locale": "fr",
            "toolbar_bg": "#f1f3f6",
            "enable_publishing": false,
            "hide_top_toolbar": false,
            "save_image": false,
            "studies": [],
            "show_popup_button": false,
            "width": "100%",
            "height": "500"
        });
    };
    container.appendChild(script);
}



// Gérer le clic sur un code de crypto
document.addEventListener("click", function(e) {
    const row = e.target.closest(".crypto-link");
    if (row && !e.target.classList.contains("watchlist-toggle")) {
        e.preventDefault(); 
        const newSymbol = row.getAttribute("data-symbol");

        // Mettre à jour le widget TradingView
        updateTradingViewSymbol(newSymbol);

        // Mettre à jour le <select> du tradingOrder si présent
        const select = document.getElementById("crypto_code");
        if (select) {
            for (let i = 0; i < select.options.length; i++) {
                if (select.options[i].value === newSymbol) {
                    select.selectedIndex = i;
                    break;
                }
            }

            const event = new Event("change");
            select.dispatchEvent(event);
        }
    }
});


window.onload = function() {
    updateTradingViewSymbol("BTCUSDT");
    refreshWatchlistData();
};


// Rafraîchir la watchlist toutes les 5 secondes (5000 millisecondes)
setInterval(refreshWatchlistData, 5000);