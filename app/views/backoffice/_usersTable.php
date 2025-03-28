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
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= $u['id_utilisateur'] ?></td>
                    <td><?= htmlspecialchars($u['pseudo']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['role']) ?></td>
                    <td><?= nl2br(htmlspecialchars($u['bio'])) ?></td>
                    <td>
                        <?php if (!empty($u['image_profil'])): ?>
                            <img src="<?= htmlspecialchars($u['image_profil']) ?>" alt="Image" style="max-width: 60px;">
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
    <?php if ($_SESSION['user']['id_utilisateur'] != $u['id_utilisateur']): ?>
        <a href="index.php?pageback=deleteUser&id=<?= $u['id_utilisateur'] ?>" onclick="return confirm('Supprimer cet utilisateur ?')">🗑️</a>
        |
        <a href="index.php?pageback=toggleUserRole&id=<?= $u['id_utilisateur'] ?>" onclick="return confirm('Changer le rôle de cet utilisateur ?')">
            🔄 <?= $u['role'] === 'admin' ? 'Déclasser' : 'Promouvoir' ?>
        </a>
    <?php else: ?>
        (vous)
    <?php endif; ?>
</td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>