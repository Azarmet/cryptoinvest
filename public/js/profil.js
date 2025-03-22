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
