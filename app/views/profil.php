<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once RACINE . 'app/views/templates/header.php';

?>

<div class="profile-container">
    <h2>Mon Profil</h2>

    <!-- Affichage des informations de profil -->
    <div id="profile-display" class="profil-section">
    <div class="profil-header">
        <img src="<?= htmlspecialchars($user['image_profil']) ?>" alt="Photo de profil" class="profile-image">
        <div class="profil-info">
            <h2><?= htmlspecialchars($user['pseudo']) ?></h2>
            <?php if (!empty($user['bio'])): ?>
                <p class="bio"><?= nl2br(htmlspecialchars($user['bio'])) ?></p>
            <?php else: ?>
                <p class="bio muted">Aucune bio pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="social-display">
        <?php if (!empty($user['instagram'])): ?>
            <p><strong>Instagram :</strong> 
                <a href="<?= htmlspecialchars($user['instagram']) ?>" target="_blank">
                    <?= htmlspecialchars($user['instagram']) ?>
                </a>
            </p>
        <?php endif; ?>

        <?php if (!empty($user['x'])): ?>
            <p><strong>X :</strong> 
                <a href="<?= htmlspecialchars($user['x']) ?>" target="_blank">
                    <?= htmlspecialchars($user['x']) ?>
                </a>
            </p>
        <?php endif; ?>

        <?php if (!empty($user['telegram'])): ?>
            <p><strong>Telegram :</strong> @<?= htmlspecialchars(ltrim($user['telegram'], '@')) ?></p>
        <?php endif; ?>
    </div>

    <div class="profile-actions">
        <button id="btn-modify" class="btn-modify">Modifier le profil</button>
    </div>
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
            <div class="form-group">
    <label for="instagram">Lien Instagram</label>
    <input type="text" name="instagram" id="instagram" placeholder="https://instagram.com/tonprofil" 
           value="<?= htmlspecialchars($user['instagram'] ?? '') ?>">
</div>

<div class="form-group">
    <label for="x">Lien X (ancien Twitter)</label>
    <input type="text" name="x" id="x" placeholder="https://x.com/tonprofil" 
           value="<?= htmlspecialchars($user['x'] ?? '') ?>">
</div>

<div class="form-group">
    <label for="telegram">Username Telegram</label>
    <input type="text" name="telegram" id="telegram" placeholder="@tonpseudo" 
           value="<?= htmlspecialchars($user['telegram'] ?? '') ?>">
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
