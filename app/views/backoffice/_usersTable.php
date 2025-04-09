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
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
    <td data-label="ID"><?= $u['id_utilisateur'] ?></td>
    <td data-label="Pseudo"><?= htmlspecialchars($u['pseudo']) ?></td>
    <td data-label="Email"><?= htmlspecialchars($u['email']) ?></td>
    <td data-label="Rôle">
        <span class="role-badge <?= $u['role'] === 'admin' ? 'admin' : 'user' ?>">
            <?= htmlspecialchars($u['role']) ?>
        </span>
    </td>
    <td data-label="Bio">
        <?= !empty(trim((string) ($u['bio'] ?? '')))
            ? nl2br(htmlspecialchars($u['bio']))
            : '<span class="no-bio">Pas de bio</span>' ?>
    </td>
    <td data-label="Image">
        <?php if (!empty($u['image_profil'])): ?>
            <img src="<?= htmlspecialchars($u['image_profil']) ?>" alt="Image de profil" class="user-avatar">
        <?php else: ?>
            <span class="no-image">-</span>
        <?php endif; ?>
    </td>
    <td td class="actions-cell" data-label="Action">
        <?php if ($_SESSION['user']['id_utilisateur'] != $u['id_utilisateur']): ?>
            <a href="index.php?pageback=deleteUser&id=<?= $u['id_utilisateur'] ?>"
               class="action-btn delete"
               title="Supprimer"
               onclick="return confirm('Supprimer cet utilisateur ?')">🗑️</a>

            <a href="index.php?pageback=toggleUserRole&id=<?= $u['id_utilisateur'] ?>"
               class="action-btn toggle role-change"
               title="Changer rôle"
               onclick="return confirm('Changer le rôle de cet utilisateur ?')">
               <?= $u['role'] === 'admin' ? 'Déclasser' : 'Promouvoir' ?>
            </a>
        <?php else: ?>
            <span class="self-label">(vous)</span>
        <?php endif; ?>
    </td>
</tr>

        <?php endforeach; ?>
    </tbody>
</table>
