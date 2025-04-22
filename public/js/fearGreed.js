/**
 * Récupère et affiche la Crypto Fear & Greed Index depuis l’API alternative.me
 * - Met à jour la valeur, la position de l’aiguille et la couleur du libellé
 */
function loadFearIndex() {
    // Requête GET vers l’API (cache désactivé)
    fetch("https://api.alternative.me/fng/")
    .then(res => {
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }
        return res.json();
    })
    .then(data => {
        const value = parseInt(data.data[0].value, 10);                   // Valeur numérique (0–100)
        const classification = data.data[0].value_classification;         // Libellé textuel

        // Affiche la valeur numérique
        document.getElementById("index-value").textContent = value;

        // Calcule et applique la rotation de l’aiguille (-90° à +90°)
        const angle = (value / 100) * 180 - 90;
        document.getElementById("needle").style.transform = `rotate(${angle}deg)`;

        // Met à jour le texte du libellé
        const label = document.getElementById("index-label");
        label.textContent = classification;

        // Choisit une couleur de fond selon le niveau de peur/avidité
        let color = "#f8f9fa";
        switch (classification.toLowerCase()) {
            case "extreme fear":
                color = "#e74c3c";  // Peur extrême
                break;
            case "fear":
                color = "#e67e22";  // Peur
                break;
            case "neutral":
                color = "#f1c40f";  // Neutre
                break;
            case "greed":
                color = "#2ecc71";  // Avidité
                break;
            case "extreme greed":
                color = "#27ae60";  // Avidité extrême
                break;
        }
        // Applique la couleur de fond et la couleur du texte du libellé
        label.style.backgroundColor = color;
        label.style.color = "#fff";
    })
    .catch(err => {
        // En cas d’erreur, log et affiche "Error" dans le libellé
        console.error("Erreur lors du chargement de l'indice :", err);
        document.getElementById("index-label").textContent = "Error";
    });
}

// Exécute loadFearIndex dès que le DOM est prêt
window.addEventListener("DOMContentLoaded", loadFearIndex);
