// Lorsque le DOM est entièrement chargé, on exécute la fonction principale
document.addEventListener('DOMContentLoaded', () => {

    // Récupération des éléments HTML nécessaires :
    const popup = document.getElementById('cookie-consent');         // La popup de consentement
    const acceptBtn = document.getElementById('acceptCookies');      // Le bouton "Accepter"
    const declineBtn = document.getElementById('declineCookies');    // Le bouton "Refuser"
  
    // Vérifie si l'utilisateur a déjà accepté ou refusé les cookies
    // Si aucune décision n’a encore été enregistrée, on affiche la popup
    if (!localStorage.getItem('cookiesAccepted')) {
      popup.classList.add('show'); // Affiche la popup en ajoutant une classe CSS "show"
    }
  
    // Lorsqu'on clique sur le bouton "Accepter"
    acceptBtn.addEventListener('click', () => {
      localStorage.setItem('cookiesAccepted', 'true');  // Enregistre "true" dans le localStorage
      popup.classList.remove('show');                   // Masque la popup
    });
  
    // Lorsqu'on clique sur le bouton "Refuser"
    declineBtn.addEventListener('click', () => {
      localStorage.setItem('cookiesAccepted', 'false'); // Enregistre "false" dans le localStorage
      popup.classList.remove('show');                   // Masque la popup
      // Optionnel : ici tu peux ajouter du code pour désactiver les scripts tiers si besoin
    });
  
  });
  