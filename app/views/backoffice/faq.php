<?php 
// Inclut l’en‑tête du back‑office (barre de navigation, styles, etc.)
require_once RACINE . 'app/views/backoffice/headerback.php'; 
?>

<section class="faq-section">
    <!-- Titre de la section FAQ Management -->
    <h1 class="section-title">FAQ Management</h1>

    <?php if (isset($_GET['success'])): ?>
        <!-- Affiche un message de confirmation selon l’action réalisée -->
        <div class="alert alert-success">
            <?php
            switch ($_GET['success']) {
                case '1':
                    // Après ajout d’une nouvelle question
                    echo '✅ The question has been successfully added.';
                    break;
                case '2':
                    // Après mise à jour d’une question existante
                    echo '✅ The question has been successfully updated.';
                    break;
                case '3':
                    // Après suppression d’une question
                    echo '✅ The question has been successfully deleted.';
                    break;
            }
            ?>
        </div>
    <?php endif; ?>

    <!-- Lien vers le formulaire de création d’une nouvelle FAQ -->
    <a href="index.php?pageback=createFaq" class="btn btn-add-faq">➕ Add a new question</a>

    <?php if (!empty($faqs)): ?>
        <!-- Tableau listant toutes les FAQ existantes -->
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
                            <!-- Colonne : Identifiant de la FAQ -->
                            <td data-label="ID"><?= htmlspecialchars($faq['id_faq']) ?></td>
                            <!-- Colonne : Question (échappée pour éviter l’injection) -->
                            <td data-label="Question"><?= htmlspecialchars($faq['question']) ?></td>
                            <!-- Colonne : Réponse (échappée + nl2br pour conserver les sauts de ligne) -->
                            <td data-label="Answer"><?= nl2br(htmlspecialchars($faq['reponse'])) ?></td>
                            <!-- Colonne : Boutons d’édition et de suppression -->
                            <td data-label="Actions" class="faq-actions">
                                <!-- Lien pour éditer la FAQ sélectionnée -->
                                <a href="index.php?pageback=editFaq&id=<?= $faq['id_faq'] ?>" 
                                   class="faq-btn edit" 
                                   title="Edit FAQ">
                                    ✏️
                                </a>
                                <!-- Lien pour supprimer la FAQ (confirmation JavaScript) -->
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
        <!-- Message affiché quand aucune FAQ n’est enregistrée -->
        <p class="no-faq">No questions recorded.</p>
    <?php endif; ?>
</section>

<?php 
// Inclut le pied de page du back‑office
require_once RACINE . 'app/views/backoffice/footerback.php'; 
?>
