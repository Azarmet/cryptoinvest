// ------------------ RAFRAÎCHISSEMENT DU TABLEAU TOP10 VIA AJAX ------------------

// Fonction qui récupère les données du marché et met à jour le tableau
function refreshMarketData(category = "top") {
    // Création de la requête AJAX
    var xhr = new XMLHttpRequest();
    xhr.open(
        "GET",
        "index.php?page=market&action=refresh&categorie=" + category,
        true
    );

    // Gestion de la réponse
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Parse la réponse JSON
            var cryptos = JSON.parse(xhr.responseText);
            // Sélection du <tbody> du tableau Top10
            var tbody = document.querySelector("#top10-market");
            var htmlContent = "";

            // Pour chaque crypto reçue, on construit une ligne de tableau
            cryptos.forEach(function(crypto) {
                var variation = parseFloat(crypto.variation_24h).toFixed(2);
                // Détermine la classe CSS selon la variation positive ou négative
                let colorClass = variation >= 0 ? 'positive' : 'negative';

                htmlContent += "<tr>";
                htmlContent += "<td>" + crypto.code + "</td>";
                htmlContent +=
                    "<td class='" + colorClass + "'>" + crypto.prix_actuel + "</td>";
                htmlContent +=
                    "<td class='" + colorClass + "'>" + variation + "%" + "</td>";
                htmlContent += "</tr>";
            });

            // Injection du HTML généré dans le tableau
            tbody.innerHTML = htmlContent;
        }
    };

    // Envoi de la requête
    xhr.send();
}

// Appel initial lors du chargement de la page
window.onload = function() {
    refreshMarketData();
};

// Mise à jour automatique toutes les 5 secondes
setInterval(function() {
    refreshMarketData();
}, 5000);
