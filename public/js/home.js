// ------------------ RAFRAÎCHISSEMENT DU TABLEAU TOP10 VIA FETCH ------------------

// Fonction qui récupère les données du marché et met à jour le tableau
function refreshMarketData(category = "top") {
    const url = `index.php?page=market&action=refresh&categorie=${encodeURIComponent(category)}`;

    fetch(url)
    .then(res => {
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }
        return res.json();
    })
    .then(cryptos => {
        const tbody = document.querySelector("#top10-market");
        let htmlContent = "";

        // Pour chaque crypto reçue, on construit une ligne de tableau
        cryptos.forEach(crypto => {
            const variation = parseFloat(crypto.variation_24h).toFixed(2);
            // Détermine la classe CSS selon la variation positive ou négative
            const colorClass = variation >= 0 ? 'positive' : 'negative';

            htmlContent += "<tr>";
            htmlContent += `<td>${crypto.code}</td>`;
            htmlContent += `<td class='${colorClass}'>${crypto.prix_actuel}</td>`;
            htmlContent += `<td class='${colorClass}'>${variation}%</td>`;
            htmlContent += "</tr>";
        });

        // Injection du HTML généré dans le tableau
        tbody.innerHTML = htmlContent;
    })
    .catch(err => {
        console.error("Erreur lors du rafraîchissement des données du marché :", err);
    });
}

// Appel initial lors du chargement de la page
window.addEventListener("DOMContentLoaded", () => {
    refreshMarketData();
    // Mise à jour automatique toutes les 5 secondes
    setInterval(refreshMarketData, 5000);
});
