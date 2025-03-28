<?php require_once RACINE . 'app/views/backoffice/headerback.php'; ?>

<h1>Gestion de la FAQ</h1>
<?php if (isset($_GET['success'])): ?>
    <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-top: 15px;">
        <?php
        switch ($_GET['success']) {
            case '1':
                echo '✅ La question a été ajoutée avec succès.';
                break;
            case '2':
                echo '✅ La question a été modifiée avec succès.';
                break;
            case '3':
                echo '✅ La question a été supprimée avec succès.';
                break;
        }
        ?>
    </div>
<?php endif; ?>

<a href="index.php?pageback=createFaq" class="btn btn-primary">➕ Ajouter une nouvelle question</a>

<?php if (!empty($faqs)): ?>
    <table border="1" cellpadding="10" cellspacing="0" style="margin-top: 20px; width: 100%;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Question</th>
                <th>Réponse</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($faqs as $faq): ?>
                <tr>
                    <td><?= htmlspecialchars($faq['id_faq']) ?></td>
                    <td><?= htmlspecialchars($faq['question']) ?></td>
                    <td><?= nl2br(htmlspecialchars($faq['reponse'])) ?></td>
                    <td>
                        <a href="index.php?pageback=editFaq&id=<?= $faq['id_faq'] ?>">✏️ Modifier</a> |
                        <a href="index.php?pageback=deleteFaq&id=<?= $faq['id_faq'] ?>" onclick="return confirm('Supprimer cette FAQ ?');">🗑️ Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucune question enregistrée.</p>
<?php endif; ?>

<?php require_once RACINE . 'app/views/backoffice/footerback.php'; ?>
