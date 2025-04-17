<?php
// Inclusion de l’en‑tête du back‑office (logo, navigation, styles)
require_once RACINE . 'app/views/backoffice/headerback.php';
?>

<section class="users-section">
    <!-- Titre de la section de gestion des utilisateurs -->
    <h1 class="section-title">User Management</h1>

    <!-- Champ de recherche pour filtrer les utilisateurs en temps réel -->
    <input type="text" id="search-user" class="user-search" placeholder="Search for a user...">

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <!-- Message de succès après suppression d’un utilisateur -->
        <div class="alert alert-success">
            ✅ User deleted successfully.
        </div>
    <?php elseif (isset($_GET['error']) && $_GET['error'] === 'cannot_delete_self'): ?>
        <!-- Message d’erreur si l’utilisateur tente de se supprimer lui‑même -->
        <div class="alert alert-error">
            ⚠️ You cannot delete your own account.
        </div>
    <?php endif; ?>

    <!-- Conteneur principal affichant la table des utilisateurs ou un message si vide -->
    <div id="users-container">
        <?php if (!empty($users)): ?>
            <div id="users-data">
                <!-- Inclusion du template partiel affichant le tableau des utilisateurs -->
                <?= include RACINE . 'app/views/backoffice/_usersTable.php'; ?>
            </div>
        <?php else: ?>
            <!-- Message si aucun utilisateur n’est trouvé -->
            <p class="no-user">No user found.</p>
        <?php endif; ?>
    </div>
</section>

<?php
// Inclusion du pied de page du back‑office (mentions, déconnexion, etc.)
require_once RACINE . 'app/views/backoffice/footerback.php';
?>

<script>
    // Stockage de l’ID de l’utilisateur courant pour la logique JS (ex. désactivation de certaines actions)
    window.currentUserId = <?= $_SESSION['user']['id_utilisateur'] ?>;
</script>
<!-- Chargement du script de recherche dynamique des utilisateurs -->
<script src="<?= RACINE_URL; ?>public/js/searchUser.js"></script>
