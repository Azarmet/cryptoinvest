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

                        tr.innerHTML = `
                            <td class="rank-${user.rank}">${user.rank}${medal}</td>
                            <td class="td-pseudo">
                                <img src="${user.image}" alt="Profil" width="25"> ${user.pseudo}
                            </td>
                            <td>${user.solde}</td>
                            <td class="${user.pnl_24h.startsWith('-') ? 'negative' : 'positive'}">${user.pnl_24h}</td>
                            <td class="${user.pnl_7j.startsWith('-') ? 'negative' : 'positive'}">${user.pnl_7j}</td>
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
