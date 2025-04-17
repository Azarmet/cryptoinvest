document.addEventListener('DOMContentLoaded', function () {
  // Récupération des éléments du sélecteur personnalisé
  const customSelect   = document.getElementById('crypto_code_custom');    // conteneur du sélecteur personnalisé
  const hiddenSelect   = document.getElementById('crypto_code');           // <select> HTML masqué
  const selectedOption = customSelect.querySelector('.selected-option');   // zone affichant la sélection actuelle
  const optionsList    = customSelect.querySelector('.options-list');      // liste des options déroulantes
  const options        = customSelect.querySelectorAll('.option');         // chaque option cliquable

  // Au clic sur la zone de sélection, on bascule l'affichage de la liste d'options
  selectedOption.addEventListener('click', () => {
      const isVisible = optionsList.style.display === 'block';
      optionsList.style.display = isVisible ? 'none' : 'block';
  });

  // Pour chaque option, on écoute le clic pour mettre à jour la sélection
  options.forEach(option => {
      option.addEventListener('click', () => {
          // Récupérer la valeur associée à l'option cliquée
          const newValue = option.getAttribute('data-value');

          // Mettre à jour le texte affiché dans la zone de sélection
          selectedOption.textContent = newValue;

          // Fermer la liste d'options après sélection
          optionsList.style.display = 'none';

          // Synchroniser la valeur du <select> masqué avec la nouvelle valeur
          hiddenSelect.value = newValue;

          // Déclencher l'événement 'change' pour informer les autres scripts du changement
          const changeEvent = new Event('change');
          hiddenSelect.dispatchEvent(changeEvent);
      });
  });
});
