// Fonction de rafraîchissement dynamique du tableau de la watchlist via AJAX
function refreshWatchlistData() {
    var xhr = new XMLHttpRequest();
    // Ajoute un paramètre timestamp pour éviter la mise en cache des requêtes
    xhr.open("GET", "index.php?page=watchlist&action=refresh&timestamp=" + new Date().getTime(), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Analyse la réponse JSON reçue
            var cryptos = JSON.parse(xhr.responseText);
            var tbody = document.querySelector("#watchlist-table tbody");
            // Vide le contenu actuel du tableau
            tbody.innerHTML = "";
            if (cryptos.length > 0) {
                cryptos.forEach(function(crypto) {
                    // Formatage de la variation à deux décimales
                    var variation = parseFloat(crypto.variation_24h).toFixed(2);
                    // Choix de la classe CSS selon le signe de la variation
                    let colorClass = variation >= 0 ? 'positive' : 'negative';
                    // Construction de la ligne HTML pour chaque crypto
                    var row = "<tr class='crypto-link' data-symbol='" + crypto.code + "'>" +
                              "<td>" + crypto.code + "</td>" +
                              "<td class='" + colorClass + "'>" + crypto.prix_actuel + "</td>" +
                              "<td class='" + colorClass + "'>" + variation + "%" + "</td>" +
                              "<td>" + crypto.date_maj + "</td>" +
                              "<td><a href='index.php?page=watchlist&action=remove&id=" + crypto.id_crypto_market + "'>Remove</a></td>" +
                              "</tr>";
                    tbody.innerHTML += row;
                });
            } else {
                // Message affiché si la watchlist est vide
                tbody.innerHTML = "<tr><td colspan='5'>Your watchlist is empty.</td></tr>";
            }
        }
    };
    xhr.send();
}

// Fonction de mise à jour du widget TradingView avec un nouveau symbole
function updateTradingViewSymbol(symbol) {
    const container = document.querySelector("#tradingview-widget-container");
    // Vide le conteneur pour insérer un nouveau widget
    container.innerHTML = "";

    // Création d’un nouvel élément <div> pour le widget
    const widgetDiv = document.createElement("div");
    widgetDiv.id = "tradingview_abcdef";
    container.appendChild(widgetDiv);

    // Chargement du script TradingView
    const script = document.createElement("script");
    script.type = "text/javascript";
    script.src = "https://s3.tradingview.com/tv.js";
    script.onload = function () {
        // Initialisation du widget avec les paramètres souhaités
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

// Gestion du clic sur une ligne de crypto pour changer le symbole du widget
document.addEventListener("click", function(e) {
    const row = e.target.closest(".crypto-link");
    // On ignore les clics sur le bouton de watchlist
    if (row && !e.target.classList.contains("watchlist-toggle")) {
        e.preventDefault();
        // Récupère le symbole de la crypto cliquée
        const newSymbol = row.getAttribute("data-symbol");

        // Met à jour le widget TradingView
        updateTradingViewSymbol(newSymbol);

        // Met à jour, si présent, le <select> du formulaire de trading
        const select = document.getElementById("crypto_code");
        if (select) {
            for (let i = 0; i < select.options.length; i++) {
                if (select.options[i].value === newSymbol) {
                    select.selectedIndex = i;
                    break;
                }
            }
            // Déclenche l'événement 'change' pour notifier les autres scripts
            const event = new Event("change");
            select.dispatchEvent(event);
        }
    }
});

// Au chargement de la page, initialise le widget par défaut et charge la watchlist
window.onload = function() {
    updateTradingViewSymbol("BTCUSDT");
    refreshWatchlistData();
};

// Rafraîchit automatiquement la watchlist toutes les 5 secondes
setInterval(refreshWatchlistData, 5000);
