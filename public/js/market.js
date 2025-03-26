// Variable globale pour stocker la catégorie courante
let currentCategory = 'top';

// Fonction de rafraîchissement du tableau via AJAX
function refreshMarketData(category = currentCategory) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "index.php?page=market&action=refresh&categorie=" + category, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var cryptos = JSON.parse(xhr.responseText);
            var tbody = document.querySelector("#market-table tbody");
            var htmlContent = "";
            cryptos.forEach(function(crypto) {
                var variation = parseFloat(crypto.variation_24h).toFixed(2);
                htmlContent += "<tr>";
                htmlContent += "<td><a href='#' class='crypto-link' data-symbol='" + crypto.code + "'>" + crypto.code + "</a></td>";
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

// Gestion des onglets
document.querySelectorAll(".tab-button").forEach(button => {
    button.addEventListener("click", function() {
        document.querySelectorAll(".tab-button").forEach(btn => btn.classList.remove("active"));
        this.classList.add("active");
        // Met à jour la catégorie globale
        currentCategory = this.getAttribute("data-category");
        refreshMarketData(currentCategory);
    });
});


// Gérer le clic sur un code de crypto
document.addEventListener("click", function(e) {
    if (e.target.classList.contains("crypto-link")) {
        e.preventDefault();
        const newSymbol = e.target.getAttribute("data-symbol");
        updateTradingViewSymbol(newSymbol);
    }
});

window.onload = function() {
    updateTradingViewSymbol("BTCUSDT");
    refreshMarketData(); // ou "top10" par défaut
};

// Rafraîchir toutes les 5 secondes avec la catégorie courante
setInterval(function() {
    refreshMarketData(currentCategory);
}, 5000);