// Écoute le changement de fichier sur l’input d’ID "image"
document.getElementById("image").addEventListener("change", function (event) {
    // Récupère l’élément <img> qui affichera la prévisualisation
    const preview = document.getElementById("preview");
    // Récupère le premier fichier sélectionné par l’utilisateur
    const file = event.target.files[0];

    // Si un fichier existe et que c’est bien une image...
    if (file && file.type.startsWith("image/")) {
        // Utilise FileReader pour lire le contenu du fichier en Data URL
        const reader = new FileReader();
        reader.onload = function (e) {
            // Met à jour l’attribut src de l’image de prévisualisation
            preview.src = e.target.result;
            // Affiche l’image
            preview.style.display = "block";
        };
        // Démarre la lecture du fichier
        reader.readAsDataURL(file);
    } else {
        // Sinon, réinitialise la prévisualisation
        preview.src = "#";
        preview.style.display = "none";
    }
});
