document.addEventListener('DOMContentLoaded', function () {
    const customSelect = document.getElementById('crypto_code_custom');
    const hiddenSelect = document.getElementById('crypto_code');
    const selectedOption = customSelect.querySelector('.selected-option');
    const optionsList = customSelect.querySelector('.options-list');
    const options = customSelect.querySelectorAll('.option');
  
    selectedOption.addEventListener('click', () => {
      const isVisible = optionsList.style.display === 'block';
      optionsList.style.display = isVisible ? 'none' : 'block';
    });
  
    options.forEach(option => {
      option.addEventListener('click', () => {
        const newValue = option.getAttribute('data-value');
        selectedOption.textContent = newValue;
        optionsList.style.display = 'none';
  
        // Met à jour la valeur du <select> masqué
        hiddenSelect.value = newValue;
  
        // Déclenche l’événement change
        const changeEvent = new Event('change');
        hiddenSelect.dispatchEvent(changeEvent);
      });
    });
  });