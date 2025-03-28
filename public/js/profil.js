// Bouton pour afficher le formulaire de modification
document.getElementById('btn-modify').addEventListener('click', function(){
    document.getElementById('profile-display').style.display = 'none';
    document.getElementById('profile-edit').style.display = 'block';
});

// Bouton pour annuler la modification et revenir à l'affichage
document.getElementById('btn-cancel').addEventListener('click', function(){
    document.getElementById('profile-edit').style.display = 'none';
    document.getElementById('profile-display').style.display = 'block';
});

document.getElementById("image_profil").addEventListener("change", function (event) {
    const file = event.target.files[0];
    const preview = document.getElementById("preview-image");

    if (file && file.type.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = "block";
        };
        reader.readAsDataURL(file);
    } else {
        preview.src = "#";
        preview.style.display = "none";
    }
});
