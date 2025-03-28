document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("search-user");
    const container = document.getElementById("users-data");

    input.addEventListener("input", () => {
        const query = input.value;

        fetch(`index.php?pageback=searchUser&q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(users => {
                let html = `
                <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; margin-top: 20px;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pseudo</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Bio</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead><tbody>`;

                if (users.length === 0) {
                    html += "<tr><td colspan='7'>Aucun utilisateur trouvé.</td></tr>";
                } else {
                    users.forEach(u => {
                        const isCurrent = u.id_utilisateur == window.currentUserId;
                        html += `
                        <tr>
                            <td>${u.id_utilisateur}</td>
                            <td>${u.pseudo}</td>
                            <td>${u.email}</td>
                            <td>${u.role}</td>
                            <td>${u.bio ?? ""}</td>
                            <td>${u.image_profil ? `<img src="${u.image_profil}" style="max-width:60px;">` : '-'}</td>
                            <td>${
                                isCurrent 
                                ? '(vous)' 
                                : `<a href="index.php?pageback=deleteUser&id=${u.id_utilisateur}" onclick="return confirm('Supprimer ?')">🗑</a> |
                                   <a href="index.php?pageback=toggleUserRole&id=${u.id_utilisateur}" onclick="return confirm('Changer rôle ?')">🔄</a>`
                            }</td>
                        </tr>`;
                    });
                }

                html += "</tbody></table>";
                container.innerHTML = html;
            });
    });
});