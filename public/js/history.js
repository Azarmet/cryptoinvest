document.addEventListener('DOMContentLoaded', () => {
    // Initialisation du tri des colonnes pour la table des transactions
    const table = document.getElementById('transactionsTable');
    const headers = table.querySelectorAll('th.sortable');
  
    headers.forEach((header, index) => {
        // Affiche le curseur de type pointeur sur les en-têtes triables
        header.style.cursor = 'pointer';
  
        // Ajoute un écouteur de clic pour déclencher le tri
        header.addEventListener('click', () => {
            // Récupère le type de données de la colonne ('number', 'date' ou 'text')
            const type = header.getAttribute('data-type');
            // Récupère toutes les lignes du corps du tableau
            const rows = Array.from(table.querySelectorAll('tbody tr'));
  
            // Détermine la direction du tri : ascendant si pas déjà ascendant, sinon descendant
            const isAscending = !header.classList.contains('asc');
  
            // Réinitialise les classes de tri sur tous les en-têtes
            headers.forEach(h => h.classList.remove('asc', 'desc'));
            // Ajoute la classe correspondant à la direction actuelle
            header.classList.add(isAscending ? 'asc' : 'desc');
  
            // Fonction utilitaire pour extraire un nombre d'une chaîne (supprime les caractères non numériques)
            const cleanNumber = text =>
                parseFloat(text.replace(/[^\d.-]/g, '').replace(',', ''));
  
            // Tri des lignes selon le type de contenu
            const sortedRows = rows.sort((a, b) => {
                const aText = a.children[index].innerText.trim();
                const bText = b.children[index].innerText.trim();
  
                let aValue = aText;
                let bValue = bText;
  
                // Convertit en nombre pour le tri numérique
                if (type === 'number') {
                    aValue = cleanNumber(aText);
                    bValue = cleanNumber(bText);
                // Convertit en date pour le tri chronologique
                } else if (type === 'date') {
                    aValue = new Date(aText);
                    bValue = new Date(bText);
                }
  
                // Comparaison selon l’ordre choisi
                if (aValue < bValue) return isAscending ? -1 : 1;
                if (aValue > bValue) return isAscending ? 1 : -1;
                return 0;
            });
  
            // Vide le corps du tableau et y réinsère les lignes triées
            const tbody = table.querySelector('tbody');
            tbody.innerHTML = '';
            sortedRows.forEach(row => tbody.appendChild(row));
        });
    });
});
