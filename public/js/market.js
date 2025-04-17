// ------------------ VARIABLE GLOBALE DE CATÉGORIE ------------------
var currentCategory = 'top'; // Catégorie de cryptos affichée (valeur par défaut : "top")

// ------------------ FONCTION AJAX DE RAFRAÎCHISSEMENT DU TABLEAU ------------------
function refreshMarketData(category = currentCategory) {
    // Initialise une requête XMLHttpRequest pour récupérer les cryptos
    var xhr = new XMLHttpRequest();
    xhr.open(
        "GET",
        "index.php?page=market&action=refresh&categorie=" + category,
        true
    );
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Parse la réponse JSON
            var cryptos = JSON.parse(xhr.responseText);
            var tbody = document.querySelector("#market-table tbody");
            var htmlContent = "";

            // Génère une ligne <tr> pour chaque crypto
            cryptos.forEach(function(crypto) {
                var variation = parseFloat(crypto.variation_24h).toFixed(2);
                let colorClass = variation >= 0 ? 'positive' : 'negative';

                htmlContent += `<tr class="crypto-link" data-symbol="${crypto.code}">`;
                htmlContent += `<td>${crypto.code}</td>`;
                htmlContent += `<td class="${colorClass}">${crypto.prix_actuel}</td>`;
                htmlContent += `<td class="${colorClass}">${variation}%</td>`;

                // Si l'utilisateur est connecté, affiche le bouton watchlist
                if (isLoggedIn) {
                    if (crypto.in_watchlist) {
                        htmlContent += `<td><button class="watchlist-toggle" data-action="remove" data-id="${crypto.id_crypto_market}">✅</button></td>`;
                    } else {
                        htmlContent += `<td><button class="watchlist-toggle" data-action="add" data-id="${crypto.id_crypto_market}">❌</button></td>`;
                    }
                }

                htmlContent += `</tr>`;
            });

            // Injecte le HTML généré dans le <tbody>
            tbody.innerHTML = htmlContent;

            // Réapplique le filtre de recherche si nécessaire
            applySearchFilter();
        }
    };
    xhr.send();
}

// ------------------ MISE À JOUR DU WIDGET TRADINGVIEW ------------------
function updateTradingViewSymbol(symbol) {
    // Vide le conteneur actuel
    const container = document.querySelector("#tradingview-widget-container");
    container.innerHTML = "";

    // Crée une nouvelle <div> pour le widget
    const widgetDiv = document.createElement("div");
    widgetDiv.id = "tradingview_abcdef";
    container.appendChild(widgetDiv);

    // Charge le script externe de TradingView
    const script = document.createElement("script");
    script.type = "text/javascript";
    script.src = "https://s3.tradingview.com/tv.js";
    script.onload = function () {
        // Initialise le widget avec le symbole passé en paramètre
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

// ------------------ GESTION DES ONGLETS DE CATÉGORIE ------------------
document.addEventListener("DOMContentLoaded", () => {
    const container = document.querySelector(".market-tabs");
    const tabButtons = container.querySelectorAll(".tab-button");

    // Scroll automatique vers l'onglet actif au chargement
    const activeTab = container.querySelector(".tab-button.active");
    if (activeTab) {
        activeTab.scrollIntoView({
            behavior: "smooth",
            inline: "center",
            block: "nearest"
        });
    }

    // Gestion du clic sur chaque onglet
    tabButtons.forEach(button => {
        button.addEventListener("click", function () {
            // Désactive tous les onglets
            tabButtons.forEach(btn => btn.classList.remove("active"));

            // Active l'onglet cliqué
            this.classList.add("active");

            // Met à jour la catégorie globale et rafraîchit le tableau
            currentCategory = this.getAttribute("data-category");
            refreshMarketData(currentCategory);

            // Scroll vers l'onglet cliqué
            this.scrollIntoView({
                behavior: "smooth",
                inline: "center",
                block: "nearest"
            });
        });
    });
});

// ------------------ GESTION DU CLIC SUR UNE LIGNE DE CRYPTO ------------------
document.addEventListener("click", function(e) {
    const row = e.target.closest(".crypto-link");
    // Ignorer le clic si c'est sur le bouton watchlist
    if (row && !e.target.classList.contains("watchlist-toggle")) {
        e.preventDefault();
        const newSymbol = row.getAttribute("data-symbol");

        // Met à jour le widget TradingView avec le nouveau symbole
        updateTradingViewSymbol(newSymbol);

        // Met à jour le <select> dans le formulaire d'ordre si présent
        const select = document.getElementById("crypto_code");
        if (select) {
            for (let i = 0; i < select.options.length; i++) {
                if (select.options[i].value === newSymbol) {
                    select.selectedIndex = i;
                    break;
                }
            }

            // Mise à jour visuelle du menu personnalisé
            const customDisplay = document.querySelector("#crypto_code_custom .selected-option");
            if (customDisplay) {
                customDisplay.textContent = newSymbol;
            }

            // Déclenche l'événement change
            const event = new Event("change");
            select.dispatchEvent(event);
        }
    }
});

// ------------------ GESTION DU CHANGEMENT DE SYMBOLE VIA <select> ------------------
document.addEventListener("DOMContentLoaded", function () {
    const select = document.getElementById("crypto_code");
    if (select) {
        select.addEventListener("change", function () {
            const selectedSymbol = select.value;
            updateTradingViewSymbol(selectedSymbol);
        });
    }
});

// ------------------ GESTION AJAX DU BOUTON WATCHLIST ------------------
document.addEventListener("click", function(e) {
    if (e.target.classList.contains("watchlist-toggle")) {
        const action = e.target.getAttribute("data-action");
        const id = e.target.getAttribute("data-id");

        fetch(`index.php?page=watchlist&action=${action}&id=${id}`, {
            method: "GET"
        })
        .then(response => response.text())
        .then(() => {
            // Rechargement du tableau sans recharger la page entière
            refreshMarketData();
        })
        .catch(error => console.error("AJAX Error:", error));
    }
});

// ------------------ RAFRAÎCHISSEMENT AUTOMATIQUE ------------------
// Rafraîchit le tableau toutes les 10 secondes
setInterval(function() {
    refreshMarketData(currentCategory);
}, 10000);

// ------------------ RAFRAÎCHISSEMENT DES POSITIONS ------------------
function refreshPositions() {
    fetch("index.php?page=market&action=refreshPositions")
        .then(res => res.json())
        .then(positions => {
            const tbody = document.querySelector("#positions-table tbody");
            document.getElementById('positions-number').textContent = "(" + positions.length + ")";
            tbody.innerHTML = "";
            positions.forEach(pos => {
                let tr = document.createElement('tr');

                // Classe selon le sens de la position
                let sensClass = pos.sens.toLowerCase() === 'long' ? 'positive' : 'negative';
                let pnl = parseFloat(pos.pnl);
                let pnlClass = pnl >= 0 ? 'positive' : 'negative';
                let roi = parseFloat(pos.roi);
                let roiClass = roi >= 0 ? 'positive' : 'negative';

                tr.innerHTML = `
                    <td>${pos.code}</td>
                    <td class="${sensClass}">${pos.sens}</td>
                    <td>${pos.prix_ouverture}</td>
                    <td>${pos.taille}</td>
                    <td>${pos.prix_actuel}</td>
                    <td>${pos.date_ouverture}</td>
                    <td class="${pnlClass}">${pnl.toFixed(2)}</td>
                    <td class="${roiClass}">${roi.toFixed(2)}%</td>
                    <td><a href="index.php?page=dashboard&action=closePosition&id=${pos.id_transaction}" class="close-btn">Close</a></td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(err => console.error(err));
}

// ------------------ FILTRE DE RECHERCHE DANS LE TABLEAU ------------------
document.getElementById("searchInput").addEventListener("keyup", function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll("#market-table tbody tr");

    rows.forEach(function(row) {
        if (row.textContent.toLowerCase().includes(searchTerm)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});

function applySearchFilter() {
    const searchTerm = document.getElementById("searchInput").value.toLowerCase();
    const rows = document.querySelectorAll("#market-table tbody tr");

    rows.forEach(function(row) {
        if (row.textContent.toLowerCase().includes(searchTerm)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}

// ------------------ RAFRAÎCHISSEMENT DU SOLDE DISPONIBLE ------------------
function refreshPortfolioData() {
    fetch(`index.php?page=market&action=available-balance`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('available-balance').textContent =
                "Available Balance: " + data.availableBalance.toFixed(2) + " USDT";
        })
        .catch(err => console.error(err));
}

// ------------------ LANCEMENT INITIAL ------------------
if (isLoggedIn) {
    refreshPositions();
}
refreshMarketData();
updateTradingViewSymbol("BTCUSDT");
if (isLoggedIn) {
    refreshPortfolioData();
    setInterval(refreshPositions, 10000);
}
