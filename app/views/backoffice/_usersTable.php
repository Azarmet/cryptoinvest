<!-- 
    Tableau des utilisateurs pour le back-office 
    Affiche chaque champ de l’utilisateur et les actions disponibles
-->
<table class="users-table">
    <thead>
        <tr>
            <!-- En-têtes de colonnes -->
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Bio</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
            <!-- Parcours de la liste des utilisateurs -->
            <tr>
                <!-- Colonne : Identifiant de l’utilisateur -->
                <td data-label="ID"><?= $u['id_utilisateur'] ?></td>
                
                <!-- Colonne : Pseudo (échapé pour éviter les injections) -->
                <td data-label="Username"><?= htmlspecialchars($u['pseudo']) ?></td>
                
                <!-- Colonne : Adresse e‑mail (échapée pour la sécurité) -->
                <td data-label="Email"><?= htmlspecialchars($u['email']) ?></td>
                
                <!-- Colonne : Rôle (badge visuel selon rôle) -->
                <td data-label="Role">
                    <span class="role-badge <?= $u['role'] === 'admin' ? 'admin' : 'user' ?>">
                        <?= htmlspecialchars($u['role']) ?>
                    </span>
                </td>
                
                <!-- Colonne : Biographie (affichée en conservant les retours à la ligne) -->
                <td data-label="Bio">
                    <?= !empty(trim((string) ($u['bio'] ?? '')))
                        ? nl2br(htmlspecialchars($u['bio']))  /* nl2br pour afficher les sauts de ligne */
                        : '<span class="no-bio">No bio</span>' /* message si absence de bio */ ?>
                </td>
                
                <!-- Colonne : Image de profil ou placeholder si absente -->
                <td data-label="Image">
                    <?php if (!empty($u['image_profil'])): ?>
                        <img src="<?= htmlspecialchars($u['image_profil']) ?>"
                             alt="Profile Image"
                             class="user-avatar">
                    <?php else: ?>
                        <span class="no-image">-</span> <!-- Aucun avatar disponible -->
                    <?php endif; ?>
                </td>
                
                <!-- Colonne : Actions (édition de rôle, suppression) -->
                <td class="actions-cell" data-label="Action">
                    <?php if ($_SESSION['user']['id_utilisateur'] != $u['id_utilisateur']): ?>
                        <!-- Lien suppression (confirmer avant action) -->
                        <a href="index.php?pageback=deleteUser&id=<?= $u['id_utilisateur'] ?>"
                           class="action-btn delete"
                           title="Delete"
                           onclick="return confirm('Delete this user?')">🗑️</a>

                        <!-- Lien promotion/dégradation de rôle -->
                        <a href="index.php?pageback=toggleUserRole&id=<?= $u['id_utilisateur'] ?>"
                           class="action-btn toggle role-change"
                           title="Change Role"
                           onclick="return confirm('Change the role of this user?')">
                            <?= $u['role'] === 'admin' ? 'Demote' : 'Promote' ?>
                        </a>
                    <?php else: ?>
                        <!-- Étiquette indiquant l’utilisateur courant (aucune action possible) -->
                        <span class="self-label">(you)</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
