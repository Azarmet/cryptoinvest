// Global variable to store the current category
var currentCategory = 'top';

// AJAX function to refresh the table
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
            
                htmlContent += `<tr class="crypto-link" data-symbol="${crypto.code}">`;
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

            // Reapply the search filter on the new content
            applySearchFilter();
        }
    };
    xhr.send();
}


function updateTradingViewSymbol(symbol) {
    const container = document.querySelector("#tradingview-widget-container");
    container.innerHTML = ""; // Clear the old widget

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
            "locale": "en", // Changed from "fr" to "en"
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

// Category tab management
document.addEventListener("DOMContentLoaded", () => {
    const container = document.querySelector(".market-tabs");
    const tabButtons = container.querySelectorAll(".tab-button");

    // Automatic scroll to the active tab on load
    const activeTab = container.querySelector(".tab-button.active");
    if (activeTab) {
        activeTab.scrollIntoView({
            behavior: "smooth",
            inline: "center",
            block: "nearest"
        });
    }

    // Handle click on each tab
    tabButtons.forEach(button => {
        button.addEventListener("click", function () {
            // Deactivate all tabs
            tabButtons.forEach(btn => btn.classList.remove("active"));

            // Activate the clicked tab
            this.classList.add("active");

            // Update the global category
            currentCategory = this.getAttribute("data-category");
            refreshMarketData(currentCategory);

            // Scroll to the active button
            this.scrollIntoView({
                behavior: "smooth",
                inline: "center",
                block: "nearest"
            });
        });
    });
});
  
// Handle click on a crypto code row
document.addEventListener("click", function(e) {
    const row = e.target.closest(".crypto-link");
    if (row && !e.target.classList.contains("watchlist-toggle")) {
        e.preventDefault(); 
        const newSymbol = row.getAttribute("data-symbol");

        // Update the TradingView widget
        updateTradingViewSymbol(newSymbol);

        // Update the <select> element in tradingOrder if present
        const select = document.getElementById("crypto_code");
        if (select) {
            for (let i = 0; i < select.options.length; i++) {
                if (select.options[i].value === newSymbol) {
                    select.selectedIndex = i;
                    break;
                }
            }

            // MAJ visuelle du menu personnalisé
            const customDisplay = document.querySelector("#crypto_code_custom .selected-option");
            if (customDisplay) {
                customDisplay.textContent = newSymbol;
            }

            // Déclencher le changement
            const event = new Event("change");
            select.dispatchEvent(event);
        }
    }
});


// Handle selection from the crypto code menu for the widget 
document.addEventListener("DOMContentLoaded", function () {
    const select = document.getElementById("crypto_code");
    if (select) {
        select.addEventListener("change", function () {
            const selectedSymbol = select.value;
            updateTradingViewSymbol(selectedSymbol);
        });
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
            refreshMarketData(); // Refresh just the table, not the whole page
        })
        .catch(error => console.error("AJAX Error:", error));
    }
});

// Refresh every 10 seconds with the current category
setInterval(function() {
    refreshMarketData(currentCategory);
}, 10000);

// Refresh positions
function refreshPositions() {
    fetch("index.php?page=market&action=refreshPositions")
        .then(res => res.json())
        .then(positions => {
            const tbody = document.querySelector("#positions-table tbody");
            document.getElementById('positions-number').textContent = "(" + positions.length + ")" ;
            tbody.innerHTML = "";
            positions.forEach(pos => {
                let tr = document.createElement('tr');
            
                // Color based on side
                let sensClass = pos.sens.toLowerCase() === 'long' ? 'positive' : 'negative';
            
                // PnL color
                let pnl = parseFloat(pos.pnl);
                let pnlClass = pnl >= 0 ? 'positive' : 'negative';
            
                // ROI color
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

// Search filter on market table
document.getElementById("searchInput").addEventListener("keyup", function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll("#market-table tbody tr");
    
    rows.forEach(function(row) {
        // Check if the row text contains the search term
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


// Refresh available balance in the portfolio section
function refreshPortfolioData() {
    fetch(`index.php?page=market&action=available-balance`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('available-balance').textContent = "Available Balance: " + data.availableBalance.toFixed(2) + " USDT";
        })
        .catch(err => console.error(err));
}

if (isLoggedIn) {
    refreshPositions();
}

refreshMarketData();

updateTradingViewSymbol("BTCUSDT");

if (isLoggedIn) {
    refreshPortfolioData();
}
if (isLoggedIn) {
    setInterval(refreshPositions, 10000);
}
