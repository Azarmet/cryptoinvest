// Attendre que le DOM soit entièrement chargé avant d’exécuter le script
document.addEventListener('DOMContentLoaded', function() {
    // Sélectionne le bouton "burger" (icône de menu mobile)
    const burger = document.getElementById('burger');
    // Sélectionne la liste des liens de navigation
    const navLinks = document.querySelector('.nav-links');

    // Ajoute un gestionnaire de clic sur le bouton burger
    burger.addEventListener('click', function() {
        // Bascule l’affichage du menu mobile en ajoutant/supprimant la classe 'nav-active'
        navLinks.classList.toggle('nav-active');
        // Anime l’icône burger (transformation en croix ou retour en burger)
        burger.classList.toggle('toggle');
    });
});
