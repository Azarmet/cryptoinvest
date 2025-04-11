const searchInput = document.getElementById('search-input');

if (searchInput) {
    searchInput.addEventListener('input', function () {
        const search = this.value;
        const xhr = new XMLHttpRequest();

        xhr.open("GET", "index.php?page=leaderboard&action=search&term=" + encodeURIComponent(search), true);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const data = JSON.parse(xhr.responseText);
                const tbody = document.querySelector('.leaderboard-table tbody');
                tbody.innerHTML = '';

                if (data.length > 0) {
                    data.forEach(user => {
                        let medal = '';
                        if (user.rank === 1) medal = ' 🥇';
                        else if (user.rank === 2) medal = ' 🥈';
                        else if (user.rank === 3) medal = ' 🥉';

                        const tr = document.createElement('tr');
                        tr.style.cursor = 'pointer';
                        tr.onclick = () => {
                            window.location.href = `index.php?page=profilboard&pseudo=${user.pseudo}`;
                        };

                        const soldeNettoye = parseFloat(user.solde.replace(/\s/g, '').replace(',', '.'));
                        const pnl7jNettoye = parseFloat(user.pnl_7j.replace(/\s/g, '').replace(',', '.'));
                        const pnl24hNettoye = parseFloat(user.pnl_24h.replace(/\s/g, '').replace(',', '.'));
                        
                        tr.innerHTML = `
                            <td class="rank-${user.rank}">${user.rank}${medal}</td>

                            <td class="td-pseudo">
                                <img src="${user.image}" alt="Profil" width="36" class="desktop-img"> 
                                <span class="pseudo-text">${user.pseudo}</span>
                            </td>

                            <td class="td-solde">
                                <img src="${user.image}" alt="Profil" width="36" class="mobile-img">
                                ${Math.round(soldeNettoye)}$
                            </td>

                            <td class="${user.pnl_24h.startsWith('-') ? 'negative' : 'positive'}">${Math.round(pnl24hNettoye)}$</td>
                            <td class="${user.pnl_7j.startsWith('-') ? 'negative' : 'positive'}">${Math.round(pnl7jNettoye)}$</td>
                        `;


                        tbody.appendChild(tr);
                    });
                } else {
                    tbody.innerHTML = `<tr><td colspan="5" style="text-align:center;">Aucun utilisateur trouvé</td></tr>`;
                }
            }
        };

        xhr.send();
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const tableHeaders = document.querySelectorAll('.leaderboard-table thead th.sortable');

    tableHeaders.forEach(header => {
        header.addEventListener('click', function () {
            // Retirer la classe active et l'attribut data-order des autres colonnes
            tableHeaders.forEach(th => {
                if (th !== this) {
                    th.removeAttribute('data-order');
                    th.classList.remove('active');
                }
            });

            // Récupère l'ordre actuel s'il existe, sinon part de "desc"
            let currentOrder = this.getAttribute('data-order') || 'desc';
            // Inverse l'ordre
            let order = currentOrder === 'asc' ? 'desc' : 'asc';
            this.setAttribute('data-order', order);
            // Ajoute la classe "active" sur l'en-tête courant
            this.classList.add('active');

            const table = this.closest('table');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));

            // Récupère l'index de la colonne cliquée
            const headerIndex = Array.from(this.parentElement.children).indexOf(this);

            // Trie les lignes du tableau en fonction du contenu numérique de la cellule
            rows.sort((rowA, rowB) => {
                const cellA = rowA.children[headerIndex].innerText.trim();
                const cellB = rowB.children[headerIndex].innerText.trim();

                // Extraction et conversion des valeurs numériques,
                // en retirant les caractères non numériques et en remplaçant la virgule par un point
                const numA = parseFloat(cellA.replace(/[^0-9\-,.]/g, '').replace(/\s/g, '').replace(',', '.')) || 0;
                const numB = parseFloat(cellB.replace(/[^0-9\-,.]/g, '').replace(/\s/g, '').replace(',', '.')) || 0;

                return order === 'asc' ? numA - numB : numB - numA;
            });

            // Réinjecte les lignes triées dans le tbody
            rows.forEach(row => tbody.appendChild(row));
        });
    });
});
