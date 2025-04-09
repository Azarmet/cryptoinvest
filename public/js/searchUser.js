document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("search-user");
    const container = document.getElementById("users-data");

    input.addEventListener("input", () => {
        const query = input.value;

        fetch(`index.php?pageback=searchUser&q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(users => {
                let html = `
                <table class="users-table">
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
                    html += "<tr><td colspan='7' class='no-user'>Aucun utilisateur trouvé.</td></tr>";
                } else {
                    users.forEach(u => {
                        const isCurrent = u.id_utilisateur == window.currentUserId;
                        const roleBadgeClass = u.role === 'admin' ? 'admin' : 'user';

                        html += `
                        <tr>
                            <td>${u.id_utilisateur}</td>
                            <td>${escapeHTML(u.pseudo)}</td>
                            <td>${escapeHTML(u.email)}</td>
                            <td><span class="role-badge ${roleBadgeClass}">${u.role}</span></td>
                            <td>${u.bio && u.bio.trim() !== ""? escapeHTML(u.bio).replace(/\n/g, "<br>"): `<span class="no-bio">Pas de bio</span>`}</td>
                            <td>${
                                u.image_profil 
                                ? `<img src="${escapeHTML(u.image_profil)}" alt="Profil" class="user-avatar">` 
                                : `<span class="no-image">-</span>`
                            }</td>
                            <td>${
                                isCurrent 
                                ? `<span class="self-label">(vous)</span>` 
                                : `
                                <a href="index.php?pageback=deleteUser&id=${u.id_utilisateur}" 
                                   class="action-btn delete" 
                                   onclick="return confirm('Supprimer cet utilisateur ?')">🗑️</a>
                                <a href="index.php?pageback=toggleUserRole&id=${u.id_utilisateur}" 
                                   class="action-btn toggle role-change" 
                                   onclick="return confirm('Changer le rôle de cet utilisateur ?')">
                                   ${u.role === 'admin' ? 'Déclasser' : 'Promouvoir'}
                                </a>`
                            }</td>
                        </tr>`;
                    });
                }

                html += "</tbody></table>";
                container.innerHTML = html;
            });
    });

    // Sécurité : échapper le HTML pour éviter les injections
    function escapeHTML(str) {
        return (str || "").replace(/[&<>'"]/g, tag => (
            {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                "'": '&#39;',
                '"': '&quot;'
            }[tag]
        ));
    }
});
