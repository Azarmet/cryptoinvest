// Sélection de l’élément input de recherche dans le leaderboard
const searchInput = document.getElementById('search-input');

if (searchInput) {
    // À chaque saisie, on interroge l’API pour filtrer les utilisateurs
    searchInput.addEventListener('input', function () {
        const search = this.value;
        const xhr = new XMLHttpRequest();

        // Requête GET vers l’action search du leaderboard avec le terme saisi
        xhr.open(
            "GET",
            "index.php?page=leaderboard&action=search&term=" + encodeURIComponent(search),
            true
        );

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const data = JSON.parse(xhr.responseText);
                const tbody = document.querySelector('.leaderboard-table tbody');
                tbody.innerHTML = '';

                if (data.length > 0) {
                    // Pour chaque utilisateur retourné, on construit une ligne cliquable
                    data.forEach(user => {
                        let medal = '';
                        if (user.rank === 1) medal = ' 🥇';
                        else if (user.rank === 2) medal = ' 🥈';
                        else if (user.rank === 3) medal = ' 🥉';

                        const tr = document.createElement('tr');
                        tr.style.cursor = 'pointer';
                        // Clic redirige vers la page de profilboard de l’utilisateur
                        tr.onclick = () => {
                            window.location.href = `index.php?page=profilboard&pseudo=${user.pseudo}`;
                        };

                        // Nettoyage des chaînes pour extraire des nombres
                        const soldeNettoye = parseFloat(
                            user.solde.replace(/\s/g, '').replace(',', '.')
                        );
                        const pnl7jNettoye = parseFloat(
                            user.pnl_7j.replace(/\s/g, '').replace(',', '.')
                        );
                        const pnl24hNettoye = parseFloat(
                            user.pnl_24h.replace(/\s/g, '').replace(',', '.')
                        );

                        // Construction du HTML de la ligne
                        tr.innerHTML = `
                            <td class="rank-${user.rank}">${user.rank}${medal}</td>

                            <td class="td-pseudo">
                                <img src="${user.image}" alt="Profile" width="36" class="desktop-img"> 
                                <span class="pseudo-text">${user.pseudo}</span>
                            </td>

                            <td class="td-solde">
                                <img src="${user.image}" alt="Profile" width="36" class="mobile-img">
                                ${Math.round(soldeNettoye)}$
                            </td>

                            <td class="${user.pnl_24h.startsWith('-') ? 'negative' : 'positive'}">
                                ${Math.round(pnl24hNettoye)}$
                            </td>
                            <td class="${user.pnl_7j.startsWith('-') ? 'negative' : 'positive'}">
                                ${Math.round(pnl7jNettoye)}$
                            </td>
                        `;

                        tbody.appendChild(tr);
                    });
                } else {
                    // Message si aucun utilisateur trouvé
                    tbody.innerHTML = `
                        <tr><td colspan="5" style="text-align:center;">
                            No user found
                        </td></tr>`;
                }
            }
        };

        xhr.send();
    });
}

// Ajout du tri dynamique sur les colonnes « sortable » du leaderboard
document.addEventListener('DOMContentLoaded', function () {
    const tableHeaders = document.querySelectorAll('.leaderboard-table thead th.sortable');

    tableHeaders.forEach(header => {
        header.addEventListener('click', function () {
            // Réinitialise les autres colonnes (classe active et ordre)
            tableHeaders.forEach(th => {
                if (th !== this) {
                    th.removeAttribute('data-order');
                    th.classList.remove('active');
                }
            });

            // Récupère l’ordre actuel ou initialise à 'desc'
            let currentOrder = this.getAttribute('data-order') || 'desc';
            // Inverse l’ordre
            let order = currentOrder === 'asc' ? 'desc' : 'asc';
            this.setAttribute('data-order', order);
            this.classList.add('active');

            const table = this.closest('table');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));

            // Indice de la colonne cliquée
            const headerIndex = Array.from(this.parentElement.children).indexOf(this);

            // Tri des lignes selon la valeur numérique de chaque cellule
            rows.sort((rowA, rowB) => {
                const cellA = rowA.children[headerIndex].innerText.trim();
                const cellB = rowB.children[headerIndex].innerText.trim();

                // Extrait et convertit en nombre en supprimant les caractères non numériques
                const numA = parseFloat(
                    cellA.replace(/[^0-9\-,.]/g, '').replace(/\s/g, '').replace(',', '.')
                ) || 0;
                const numB = parseFloat(
                    cellB.replace(/[^0-9\-,.]/g, '').replace(/\s/g, '').replace(',', '.')
                ) || 0;

                return order === 'asc' ? numA - numB : numB - numA;
            });

            // Réinsertion des lignes triées dans le tbody
            rows.forEach(row => tbody.appendChild(row));
        });
    });
});
