// ------------------ AFFICHAGE ET ANNULATION DU FORMULAIRE DE MODIFICATION ------------------

// Lors du clic sur "Modifier", on cache l'affichage du profil et on affiche le formulaire d’édition
document.getElementById('btn-modify').addEventListener('click', function(){
    document.getElementById('profile-display').style.display = 'none';
    document.getElementById('profile-edit').style.display = 'block';
});

// Lors du clic sur "Annuler", on cache le formulaire d’édition et on réaffiche l’affichage du profil
document.getElementById('btn-cancel').addEventListener('click', function(){
    document.getElementById('profile-edit').style.display = 'none';
    document.getElementById('profile-display').style.display = 'block';
});

// ------------------ CONFIRMATION DE SUPPRESSION DU COMPTE ------------------
document.addEventListener("DOMContentLoaded", function () {
    const initDeleteBtn = document.getElementById("btn-supprimer-init");           // Bouton initial "Supprimer mon compte"
    const confirmBox = document.getElementById("confirmation-suppression");        // Lien de confirmation "Oui, supprimer"
    const cancelBtn = document.getElementById("btn-annuler-suppression");          // Bouton "Annuler" de la confirmation

    if (initDeleteBtn && confirmBox && cancelBtn) {
        // Affiche la boîte de confirmation et masque le bouton initial
        initDeleteBtn.addEventListener("click", function () {
            initDeleteBtn.style.display = "none";
            confirmBox.style.display = "block";
            cancelBtn.style.display = "block";
        });

        // En cas d'annulation, on masque la confirmation et on réaffiche le bouton initial
        cancelBtn.addEventListener("click", function () {
            confirmBox.style.display = "none";
            cancelBtn.style.display = "none";
            initDeleteBtn.style.display = "inline-block";
        });
    }
});

// ------------------ PRÉVISUALISATION DE L'IMAGE DE PROFIL ------------------

// Au changement du fichier d'image de profil, on affiche une prévisualisation
document.getElementById("image_profil").addEventListener("change", function (event) {
    const file = event.target.files[0];                          // Fichier sélectionné
    const preview = document.getElementById("preview-image");    // <img> pour prévisualisation
    const currentImage = document.getElementById("current-image");// Image actuelle du profil

    // Si le fichier est une image valide...
    if (file && file.type.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;        // Définit la source de l’image à la DataURL
            preview.style.display = "block";      // Affiche la prévisualisation
        };
        currentImage.style.display = "none";      // Cache l’image actuelle durant la prévisualisation
        reader.readAsDataURL(file);               // Lit le fichier en DataURL
    } else {
        // Sinon, on reset la prévisualisation
        preview.src = "#";
        preview.style.display = "none";
    }
});
