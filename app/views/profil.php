<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once RACINE . 'app/views/templates/header.php';

?>

<div class="profile-container">
    <h2>Mon Profil</h2>

    <!-- Affichage des informations de profil -->
    <div id="profile-display" class="profile-display">
        <div class="profile-info">
            <p><strong>Pseudo :</strong> <?php echo htmlspecialchars($_SESSION['user']['pseudo']); ?></p>
            <p><strong>Email :</strong> <?php echo htmlspecialchars($_SESSION['user']['email']); ?></p>
            <p><strong>Biographie :</strong>
                <?php
                echo isset($_SESSION['user']['bio']) && !empty($_SESSION['user']['bio'])
                    ? htmlspecialchars($_SESSION['user']['bio'])
                    : 'Aucune bio disponible.';
                ?>
            </p>
        </div>
        <?php if (isset($_SESSION['user']['image_profil']) && !empty($_SESSION['user']['image_profil'])): ?>
            <div class="profile-image">
                <img src="<?php echo htmlspecialchars($_SESSION['user']['image_profil']); ?>" alt="Image de Profil">
            </div>
        <?php endif; ?>
        <button id="btn-modify" class="btn-modify">Modifier Profil</button>
    </div>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?php
            switch ($_GET['error']) {
                case 'type':
                    echo '❌ Format de fichier non autorisé. Seules les images JPEG, PNG, GIF ou WEBP sont acceptées.';
                    break;
                case 'size':
                    echo "❌ L'image dépasse la taille maximale autorisée (10 Mo).";
                    break;
                case 'upload':
                    echo '❌ Une erreur est survenue lors du transfert du fichier.';
                    break;
                default:
                    echo '❌ Une erreur inconnue est survenue.';
            }
            ?>
        </div>
    <?php endif; ?>

    <!-- Formulaire de modification, initialement caché -->
    <div id="profile-edit" class="profile-edit" style="display: none;">
        <h3>Modifier votre profil</h3>
        <form action="index.php?page=profil&action=update" method="post" enctype="multipart/form-data" class="profile-form">
            <div class="form-group">
                <label for="bio">Biographie :</label>
                <textarea name="bio" id="bio" rows="5"><?php echo isset($_SESSION['user']['bio']) ? htmlspecialchars($_SESSION['user']['bio']) : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label for="image_profil">Changer l'image de profil :</label>
                <input type="file" name="image_profil" id="image_profil" accept="image/*">
            </div>
            <!-- Zone d'aperçu -->
            <div id="preview-container" class="preview-container">
                <img id="preview-image" src="#" alt="Aperçu de l'image" style="display: none;">
            </div>
            <?php if (isset($_SESSION['user']['image_profil']) && !empty($_SESSION['user']['image_profil'])): ?>
                <div class="current-image">
                    <img src="<?php echo htmlspecialchars($_SESSION['user']['image_profil']); ?>" alt="Image de Profil Actuelle">
                </div>
            <?php endif; ?>
            <div class="form-actions">
                <button type="submit" class="btn-submit">Enregistrer les modifications</button>
                <button type="button" id="btn-cancel" class="btn-cancel">Annuler</button>
            </div>
        </form>
    </div>
</div>

<script src="<?php echo RACINE_URL; ?>public/js/profil.js"></script>

<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
