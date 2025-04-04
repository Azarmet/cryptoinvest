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
                let colorClass = variation >= 0 ? 'positive' : 'negative';
            
                htmlContent += `<tr class="crypto-link" data-symbol="${crypto.code}">`; // <--- ici
                htmlContent += `<td>${crypto.code}</td>`;
                htmlContent += `<td class="${colorClass}">${crypto.prix_actuel}</td>`;
                htmlContent += `<td class="${colorClass}">${variation}%</td>`;
            
                if (isLoggedIn) {
                    if (crypto.in_watchlist) {
                        htmlContent += `<td><button class="watchlist-toggle" data-action="remove" data-id="${crypto.id_crypto_market}">✅</button></td>`;
                    } else {
                        htmlContent += `<td><button class="watchlist-toggle" data-action="add" data-id="${crypto.id_crypto_market}">❌</button></td>`;
                    }
                }
            
                htmlContent += `</tr>`;
            });
            
            tbody.innerHTML = htmlContent;

            // Réappliquer le filtre de recherche sur le nouveau contenu
            applySearchFilter();
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




document.addEventListener("click", function(e) {
    if (e.target.classList.contains("watchlist-toggle")) {
        const action = e.target.getAttribute("data-action");
        const id = e.target.getAttribute("data-id");

        fetch(`index.php?page=watchlist&action=${action}&id=${id}`, {
            method: "GET"
        })
        .then(response => response.text())
        .then(() => {
            refreshMarketData(); // Rafraîchir juste le tableau, pas la page
        })
        .catch(error => console.error("Erreur AJAX :", error));
    }
});


// Rafraîchir toutes les 5 secondes avec la catégorie courante
setInterval(function() {
    refreshMarketData(currentCategory);
}, 10000);

// Rafraîchissement des positions
function refreshPositions() {
    fetch("index.php?page=market&action=refreshPositions")
        .then(res => res.json())
        .then(positions => {
            const tbody = document.querySelector("#positions-table tbody");
            document.getElementById('positions-number').textContent = "(" + positions.length + ")" ;
            tbody.innerHTML = "";
            positions.forEach(pos => {
                let tr = document.createElement('tr');
            
                // Couleur selon le sens
                let sensClass = pos.sens.toLowerCase() === 'long' ? 'positive' : 'negative';
            
                // Couleur PnL
                let pnl = parseFloat(pos.pnl);
                let pnlClass = pnl >= 0 ? 'positive' : 'negative';
            
                // Couleur ROI
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
                    <td><a href="index.php?page=market&action=closePosition&id=${pos.id_transaction}">Clôturer</a></td>
                `;
                tbody.appendChild(tr);
            });
            
        })
        .catch(err => console.error(err));
}

document.getElementById("searchInput").addEventListener("keyup", function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll("#market-table tbody tr");
    
    rows.forEach(function(row) {
        // On cherche si le texte de la ligne contient le terme recherché
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


// Rafraîchissement du portefeuille (graphique, stats, valeur actuelle et solde disponible)
function refreshPortfolioData() {
    fetch(`index.php?page=market&action=available-balance`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('available-balance').textContent = "Solde disponible : " + data.availableBalance.toFixed(2) + " USDT";
        })
        .catch(err => console.error(err));
}
if(isLoggedIn){
    refreshPositions();
}

refreshMarketData();

updateTradingViewSymbol("BTCUSDT");

if(isLoggedIn){
refreshPortfolioData();
}
if(isLoggedIn){setInterval(refreshPositions, 10000);}