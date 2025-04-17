// Sélectionne tous les boutons d’onglets dans la section marché
document.querySelectorAll('.market-tab-button').forEach(btn => {
    // Ajoute un gestionnaire de clic à chaque bouton
    btn.addEventListener('click', () => {
        // Désactive visuellement tous les onglets en retirant la classe 'active'
        document.querySelectorAll('.market-tab-button').forEach(b => b.classList.remove('active'));
        // Cache tous les contenus associés en retirant la classe 'active'
        document.querySelectorAll('.market-tab-content').forEach(tab => tab.classList.remove('active'));

        // Active le bouton cliqué en lui ajoutant la classe 'active'
        btn.classList.add('active');
        // Affiche le contenu correspondant en lui ajoutant la classe 'active'
        document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
    });
});
