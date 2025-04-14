<?php require_once RACINE . 'app/views/backoffice/headerback.php'; ?>

<section class="faq-section">
    <h1 class="section-title">FAQ Management</h1>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php
            switch ($_GET['success']) {
                case '1':
                    echo '✅ The question has been successfully added.';
                    break;
                case '2':
                    echo '✅ The question has been successfully updated.';
                    break;
                case '3':
                    echo '✅ The question has been successfully deleted.';
                    break;
            }
            ?>
        </div>
    <?php endif; ?>

    <a href="index.php?pageback=createFaq" class="btn btn-add-faq">➕ Add a new question</a>

    <?php if (!empty($faqs)): ?>
        <div class="faq-table-container">
            <table class="faq-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Question</th>
                        <th>Answer</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($faqs as $faq): ?>
                        <tr>
                            <td data-label="ID"><?= htmlspecialchars($faq['id_faq']) ?></td>
                            <td data-label="Question"><?= htmlspecialchars($faq['question']) ?></td>
                            <td data-label="Answer"><?= nl2br(htmlspecialchars($faq['reponse'])) ?></td>
                            <td data-label="Actions" class="faq-actions">
                                <a href="index.php?pageback=editFaq&id=<?= $faq['id_faq'] ?>" 
                                   class="faq-btn edit" 
                                   title="Edit FAQ">
                                    ✏️
                                </a>
                                <a href="index.php?pageback=deleteFaq&id=<?= $faq['id_faq'] ?>" 
                                   class="faq-btn delete" 
                                   title="Delete FAQ"
                                   onclick="return confirm('Delete this FAQ?');">
                                    🗑️
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="no-faq">No questions recorded.</p>
    <?php endif; ?>
</section>

<?php require_once RACINE . 'app/views/backoffice/footerback.php'; ?>
