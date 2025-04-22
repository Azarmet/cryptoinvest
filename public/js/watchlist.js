// ------------------ RAFRAÎCHISSEMENT DYNAMIQUE DE LA WATCHLIST VIA FETCH ------------------
function refreshWatchlistData() {
    const url = `index.php?page=watchlist&action=refresh&timestamp=${new Date().getTime()}`;

    fetch(url)
    .then(res => {
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }
        return res.json();
    })
    .then(cryptos => {
        const tbody = document.querySelector("#watchlist-table tbody");
        tbody.innerHTML = "";

        if (cryptos.length > 0) {
            cryptos.forEach(crypto => {
                const variation = parseFloat(crypto.variation_24h).toFixed(2);
                const colorClass = variation >= 0 ? 'positive' : 'negative';

                const row = document.createElement('tr');
                row.className = 'crypto-link';
                row.setAttribute('data-symbol', crypto.code);
                row.innerHTML = `
                    <td>${crypto.code}</td>
                    <td class="${colorClass}">${crypto.prix_actuel}</td>
                    <td class="${colorClass}">${variation}%</td>
                    <td>${crypto.date_maj}</td>
                    <td><a href="index.php?page=watchlist&action=remove&id=${crypto.id_crypto_market}">Remove</a></td>
                `;

                tbody.appendChild(row);
            });
        } else {
            tbody.innerHTML = "<tr><td colspan='5'>Your watchlist is empty.</td></tr>";
        }
    })
    .catch(err => {
        console.error("Erreur lors du rafraîchissement de la watchlist :", err);
    });
}

// ------------------ MISE À JOUR DU WIDGET TRADINGVIEW ------------------
function updateTradingViewSymbol(symbol) {
    const container = document.querySelector("#tradingview-widget-container");
    container.innerHTML = "";

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

// ------------------ GESTION DU CLIC SUR UNE LIGNE DE CRYPTO ------------------
document.addEventListener("click", function(e) {
    const row = e.target.closest(".crypto-link");
    if (row && !e.target.classList.contains("watchlist-toggle")) {
        e.preventDefault();
        const newSymbol = row.getAttribute("data-symbol");

        updateTradingViewSymbol(newSymbol);

        const select = document.getElementById("crypto_code");
        if (select) {
            Array.from(select.options).forEach((opt, i) => {
                if (opt.value === newSymbol) select.selectedIndex = i;
            });
            select.dispatchEvent(new Event("change"));
        }
    }
});

// ------------------ INITIALISATION AU CHARGEMENT ------------------
window.addEventListener("DOMContentLoaded", function() {
    updateTradingViewSymbol("BTCUSDT");
    refreshWatchlistData();

    // Rafraîchissement automatique toutes les 5 secondes
    setInterval(refreshWatchlistData, 5000);
});
