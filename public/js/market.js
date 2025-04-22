// ------------------ VARIABLE GLOBALE DE CATÉGORIE ------------------
var currentCategory = 'top'; // Catégorie de cryptos affichée (valeur par défaut : "top")

// ------------------ FONCTION FETCH DE RAFRAÎCHISSEMENT DU TABLEAU ------------------
function refreshMarketData(category = currentCategory) {
    const url = `index.php?page=market&action=refresh&categorie=${encodeURIComponent(category)}`;

    fetch(url)
    .then(res => {
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }
        return res.json();
    })
    .then(cryptos => {
        const tbody = document.querySelector("#market-table tbody");
        let htmlContent = "";

        // Génère une ligne <tr> pour chaque crypto
        cryptos.forEach(crypto => {
            const variation = parseFloat(crypto.variation_24h).toFixed(2);
            const colorClass = variation >= 0 ? 'positive' : 'negative';

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
    })
    .catch(err => {
        console.error("Erreur lors du rafraîchissement des données du marché :", err);
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
            tabButtons.forEach(btn => btn.classList.remove("active"));
            this.classList.add("active");

            currentCategory = this.getAttribute("data-category");
            refreshMarketData(currentCategory);

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
    if (row && !e.target.classList.contains("watchlist-toggle")) {
        e.preventDefault();
        const newSymbol = row.getAttribute("data-symbol");

        updateTradingViewSymbol(newSymbol);

        const select = document.getElementById("crypto_code");
        if (select) {
            for (let i = 0; i < select.options.length; i++) {
                if (select.options[i].value === newSymbol) {
                    select.selectedIndex = i;
                    break;
                }
            }

            const customDisplay = document.querySelector("#crypto_code_custom .selected-option");
            if (customDisplay) {
                customDisplay.textContent = newSymbol;
            }

            select.dispatchEvent(new Event("change"));
        }
    }
});

// ------------------ GESTION DU CHANGEMENT DE SYMBOLE VIA <select> ------------------
document.addEventListener("DOMContentLoaded", function () {
    const select = document.getElementById("crypto_code");
    if (select) {
        select.addEventListener("change", function () {
            updateTradingViewSymbol(select.value);
        });
    }
});

// ------------------ GESTION AJAX DU BOUTON WATCHLIST ------------------
document.addEventListener("click", function(e) {
    if (e.target.classList.contains("watchlist-toggle")) {
        const action = e.target.getAttribute("data-action");
        const id = e.target.getAttribute("data-id");

        fetch(`index.php?page=watchlist&action=${action}&id=${id}`, {
            method: "GET",
            cache: 'no-store'
        })
        .then(res => {
            if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
            return res.text();
        })
        .then(() => {
            refreshMarketData();
        })
        .catch(error => console.error("AJAX Error:", error));
    }
});

// ------------------ RAFRAÎCHISSEMENT AUTOMATIQUE ------------------
// Rafraîchit le tableau toutes les 10 secondes
setInterval(() => {
    refreshMarketData(currentCategory);
}, 10000);

// ------------------ RAFRAÎCHISSEMENT DES POSITIONS ------------------
function refreshPositions() {
    fetch("index.php?page=market&action=refreshPositions")
    .then(res => {
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
        return res.json();
    })
    .then(positions => {
        const tbody = document.querySelector("#positions-table tbody");
        document.getElementById('positions-number').textContent = `(${positions.length})`;
        tbody.innerHTML = "";
        positions.forEach(pos => {
            const sensClass = pos.sens.toLowerCase() === 'long' ? 'positive' : 'negative';
            const pnl = parseFloat(pos.pnl);
            const pnlClass = pnl >= 0 ? 'positive' : 'negative';
            const roi = parseFloat(pos.roi);
            const roiClass = roi >= 0 ? 'positive' : 'negative';

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${pos.code}</td>
                <td class="${sensClass}">${pos.sens}</td>
                <td>${pos.prix_ouverture}</td>
                <td>${pos.taille}</td>
                <td>${pos.prix_actuel}</td>
                <td>${pos.date_ouverture}</td>
                <td class="${pnlClass}">${pnl.toFixed(2)}</td>
                <td class="${roiClass}">${roi.toFixed(2)}%</td>
                <td><a href="index.php?page=market&action=closePosition&id=${pos.id_transaction}" class="close-btn">Close</a></td>
            `;
            tbody.appendChild(tr);
        });
    })
    .catch(err => console.error(err));
}

// ------------------ FILTRE DE RECHERCHE DANS LE TABLEAU ------------------
document.getElementById("searchInput").addEventListener("keyup", function() {
    const searchTerm = this.value.toLowerCase();
    document.querySelectorAll("#market-table tbody tr").forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(searchTerm) ? "" : "none";
    });
});

function applySearchFilter() {
    const searchTerm = document.getElementById("searchInput").value.toLowerCase();
    document.querySelectorAll("#market-table tbody tr").forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(searchTerm) ? "" : "none";
    });
}

// ------------------ RAFRAÎCHISSEMENT DU SOLDE DISPONIBLE ------------------
function refreshPortfolioData() {
    fetch("index.php?page=market&action=available-balance")
    .then(res => {
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
        return res.json();
    })
    .then(data => {
        document.getElementById('available-balance').textContent =
            `Available Balance: ${data.availableBalance.toFixed(2)} USDT`;
    })
    .catch(err => console.error(err));
}

// ------------------ INIT & SCROLL SI SUCCESS ------------------
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success')) {
        const section = document.getElementById('positions-section-scroll');
        if (section) {
            section.scrollIntoView({ behavior: 'smooth' });
        }
    }
});

// ------------------ LANCEMENT INITIAL ------------------
if (isLoggedIn) {
    refreshPositions();
    refreshPortfolioData();
    setInterval(refreshPositions, 10000);
}
refreshMarketData();
updateTradingViewSymbol("BTCUSDT");
