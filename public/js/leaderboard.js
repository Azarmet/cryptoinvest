document.getElementById('search-input').addEventListener('input', function () {
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
                    const tr = document.createElement('tr');
                    tr.style.cursor = 'pointer';
                    tr.onclick = () => {
                        window.location.href = `index.php?page=profilboard&pseudo=${user.pseudo}`;
                    };

                    tr.innerHTML = `
                        <td>${user.rank}</td>
                        <td class="td-pseudo"><img src="${user.image}" alt="Profil" width="25"> ${user.pseudo}</td>
                        <td>${user.solde}</td>
                        <td class="${parseFloat(user.pnl_24h.replace(',', '.')) >= 0 ? 'positive' : 'negative'}">${user.pnl_24h}</td>
                        <td class="${parseFloat(user.pnl_7j.replace(',', '.')) >= 0 ? 'positive' : 'negative'}">${user.pnl_7j}</td>
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
