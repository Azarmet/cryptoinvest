// Function to refresh the watchlist table via AJAX
function refreshWatchlistData() {
    var xhr = new XMLHttpRequest();
    // Add a "timestamp" parameter to avoid caching
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
                              "<td>" + crypto.code + "</td>" +
                              "<td class='" + colorClass + "'>" + crypto.prix_actuel + "</td>" +
                              "<td class='" + colorClass + "'>" + variation + "%" + "</td>" +
                              "<td>" + crypto.date_maj + "</td>" +
                              "<td><a href='index.php?page=watchlist&action=remove&id=" + crypto.id_crypto_market + "'>Remove</a></td>" +
                              "</tr>";
                    tbody.innerHTML += row;
                });
            } else {
                tbody.innerHTML = "<tr><td colspan='5'>Your watchlist is empty.</td></tr>";
            }
        }
    };
    xhr.send();
}

function updateTradingViewSymbol(symbol) {
    const container = document.querySelector("#tradingview-widget-container");
    container.innerHTML = ""; // Clear the previous widget

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
            "locale": "en",
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

// Handle click on a crypto code row
document.addEventListener("click", function(e) {
    const row = e.target.closest(".crypto-link");
    if (row && !e.target.classList.contains("watchlist-toggle")) {
        e.preventDefault(); 
        const newSymbol = row.getAttribute("data-symbol");

        // Update the TradingView widget
        updateTradingViewSymbol(newSymbol);

        // Update the <select> element in tradingOrder, if present
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

// Refresh the watchlist every 5 seconds (5000 milliseconds)
setInterval(refreshWatchlistData, 5000);
