document.addEventListener("DOMContentLoaded", () => {
    // Récupération du champ de recherche et du conteneur du tableau
    const input = document.getElementById("search-user");
    const container = document.getElementById("users-data");

    // Écoute de la saisie dans le champ de recherche
    input.addEventListener("input", () => {
        const query = input.value;

        // Requête AJAX pour obtenir la liste filtrée des utilisateurs
        fetch(`index.php?pageback=searchUser&q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(users => {
                // Construction du tableau HTML avec les résultats
                let html = `
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Bio</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead><tbody>`;

                // Si aucun utilisateur trouvé, on affiche un message
                if (users.length === 0) {
                    html += "<tr><td colspan='7' class='no-user'>No users found.</td></tr>";
                } else {
                    // Pour chaque utilisateur, on génère une ligne du tableau
                    users.forEach(u => {
                        const isCurrent = u.id_utilisateur == window.currentUserId;
                        const roleBadgeClass = u.role === 'admin' ? 'admin' : 'user';

                        html += `
                        <tr>
                            <td>${u.id_utilisateur}</td>
                            <td>${escapeHTML(u.pseudo)}</td>
                            <td>${escapeHTML(u.email)}</td>
                            <td><span class="role-badge ${roleBadgeClass}">${u.role}</span></td>
                            <td>${
                                u.bio && u.bio.trim() !== "" 
                                  ? escapeHTML(u.bio).replace(/\n/g, "<br>") 
                                  : `<span class="no-bio">No bio</span>`
                            }</td>
                            <td>${
                                u.image_profil 
                                  ? `<img src="${escapeHTML(u.image_profil)}" alt="Profile" class="user-avatar">` 
                                  : `<span class="no-image">-</span>`
                            }</td>
                            <td>${
                                // Si c'est l'utilisateur courant, pas d'actions, sinon boutons
                                isCurrent 
                                  ? `<span class="self-label">(you)</span>` 
                                  : `
                                  <a href="index.php?pageback=deleteUser&id=${u.id_utilisateur}" 
                                     class="action-btn delete" 
                                     onclick="return confirm('Delete this user?')">🗑️</a>
                                  <a href="index.php?pageback=toggleUserRole&id=${u.id_utilisateur}" 
                                     class="action-btn toggle role-change" 
                                     onclick="return confirm('Change the role of this user?')">
                                     ${u.role === 'admin' ? 'Demote' : 'Promote'}
                                  </a>`
                            }</td>
                        </tr>`;
                    });
                }

                html += "</tbody></table>";
                // Injection du HTML final dans la page
                container.innerHTML = html;
            });
    });

    /**
     * Fonction de sécurité pour échapper les caractères HTML
     * et prévenir les injections.
     */
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
