<?php require_once RACINE . 'app/views/backoffice/headerback.php'; ?>

<h1>Gestion des utilisateurs</h1>

<input type="text" id="search-user" placeholder="Rechercher un utilisateur..." style="width: 300px; margin-bottom: 15px; padding: 5px;">
<div id="users-container">
    <!-- ici s'affichera la table générée dynamiquement -->
</div>

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div style="background-color: #d4edda; color: #155724; padding: 10px; margin: 15px 0; border-radius: 5px;">
        ✅ Utilisateur supprimé avec succès.
    </div>
<?php elseif (isset($_GET['error']) && $_GET['error'] === 'cannot_delete_self'): ?>
    <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin: 15px 0; border-radius: 5px;">
        ⚠️ Vous ne pouvez pas supprimer votre propre compte.
    </div>
<?php endif; ?>

<?php if (!empty($users)): ?>
    <div id="users-data">
    <?= include RACINE . 'app/views/backoffice/_usersTable.php'; ?>
</div>

<?php else: ?>
    <p>Aucun utilisateur trouvé.</p>
<?php endif; ?>

<?php require_once RACINE . 'app/views/backoffice/footerback.php'; ?>
<script>
    window.currentUserId = <?= $_SESSION['user']['id_utilisateur'] ?>;
</script>
<script src="<?php echo RACINE_URL; ?>public/js/searchUser.js"></script>