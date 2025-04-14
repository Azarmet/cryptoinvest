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
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td data-label="ID"><?= $u['id_utilisateur'] ?></td>
                <td data-label="Username"><?= htmlspecialchars($u['pseudo']) ?></td>
                <td data-label="Email"><?= htmlspecialchars($u['email']) ?></td>
                <td data-label="Role">
                    <span class="role-badge <?= $u['role'] === 'admin' ? 'admin' : 'user' ?>">
                        <?= htmlspecialchars($u['role']) ?>
                    </span>
                </td>
                <td data-label="Bio">
                    <?= !empty(trim((string) ($u['bio'] ?? '')))
                        ? nl2br(htmlspecialchars($u['bio']))
                        : '<span class="no-bio">No bio</span>' ?>
                </td>
                <td data-label="Image">
                    <?php if (!empty($u['image_profil'])): ?>
                        <img src="<?= htmlspecialchars($u['image_profil']) ?>" alt="Profile Image" class="user-avatar">
                    <?php else: ?>
                        <span class="no-image">-</span>
                    <?php endif; ?>
                </td>
                <td class="actions-cell" data-label="Action">
                    <?php if ($_SESSION['user']['id_utilisateur'] != $u['id_utilisateur']): ?>
                        <a href="index.php?pageback=deleteUser&id=<?= $u['id_utilisateur'] ?>"
                           class="action-btn delete"
                           title="Delete"
                           onclick="return confirm('Delete this user?')">🗑️</a>

                        <a href="index.php?pageback=toggleUserRole&id=<?= $u['id_utilisateur'] ?>"
                           class="action-btn toggle role-change"
                           title="Change Role"
                           onclick="return confirm('Change the role of this user?')">
                           <?= $u['role'] === 'admin' ? 'Demote' : 'Promote' ?>
                        </a>
                    <?php else: ?>
                        <span class="self-label">(you)</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
