<?php require_once RACINE . 'app/views/backoffice/headerback.php'; ?>

<section class="users-section">
    <h1 class="section-title">Gestion des utilisateurs</h1>

    <input type="text" id="search-user" class="user-search" placeholder="Rechercher un utilisateur...">

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="alert alert-success">
            ✅ Utilisateur supprimé avec succès.
        </div>
    <?php elseif (isset($_GET['error']) && $_GET['error'] === 'cannot_delete_self'): ?>
        <div class="alert alert-error">
            ⚠️ Vous ne pouvez pas supprimer votre propre compte.
        </div>
    <?php endif; ?>

    <div id="users-container">
        <?php if (!empty($users)): ?>
            <div id="users-data">
                <?= include RACINE . 'app/views/backoffice/_usersTable.php'; ?>
            </div>
        <?php else: ?>
            <p class="no-user">Aucun utilisateur trouvé.</p>
        <?php endif; ?>
    </div>
</section>

<?php require_once RACINE . 'app/views/backoffice/footerback.php'; ?>

<script>
    window.currentUserId = <?= $_SESSION['user']['id_utilisateur'] ?>;
</script>
<script src="<?= RACINE_URL; ?>public/js/searchUser.js"></script>
