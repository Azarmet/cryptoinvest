<?php
// Si la session n'est pas démarrée, on la démarre
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Inclusion de l’en-tête commun (nav, styles, etc.)
require_once RACINE . 'app/views/templates/header.php';
?>

<div class="profile-container">
    <!-- Titre de la section Profil -->
    <h2>My Profile</h2>

    <!-- Affichage des informations du profil -->
    <div id="profile-display" class="profil-section">
        <div class="profil-header">
            <!-- Photo de profil de l'utilisateur -->
            <img src="<?= htmlspecialchars($user['image_profil']) ?>" alt="Profile Picture" class="profile-image">
            <div class="profil-info">
                <!-- Pseudo de l'utilisateur -->
                <h2><?= htmlspecialchars($user['pseudo']) ?></h2>
                <!-- Biographie ou message par défaut -->
                <?php if (!empty($user['bio'])): ?>
                    <p class="bio"><?= nl2br(htmlspecialchars($user['bio'])) ?></p>
                <?php else: ?>
                    <p class="bio muted">No bio available yet.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Liens vers les réseaux sociaux (optionnels) -->
        <div class="social-display">
            <?php if (!empty($user['instagram'])): ?>
                <p><strong>Instagram:</strong> 
                    <a href="<?= htmlspecialchars($user['instagram']) ?>" target="_blank">
                        <?= htmlspecialchars($user['instagram']) ?>
                    </a>
                </p>
            <?php endif; ?>

            <?php if (!empty($user['x'])): ?>
                <p><strong>X:</strong> 
                    <a href="<?= htmlspecialchars($user['x']) ?>" target="_blank">
                        <?= htmlspecialchars($user['x']) ?>
                    </a>
                </p>
            <?php endif; ?>

            <?php if (!empty($user['telegram'])): ?>
                <p><strong>Telegram:</strong> @<?= htmlspecialchars(ltrim($user['telegram'], '@')) ?></p>
            <?php endif; ?>
        </div>

        <!-- Bouton pour passer en mode édition -->
        <div class="profile-actions">
            <button id="btn-modify" class="btn-modify">Edit Profile</button>
        </div>
    </div>

    <!-- Affichage des messages d'erreur issus du contrôleur -->
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?php
            switch ($_GET['error']) {
                case 'type':
                    echo '❌ File format not allowed. Only JPEG, PNG, GIF, or WEBP images are accepted.';
                    break;
                case 'size':
                    echo "❌ The image exceeds the maximum allowed size (10 MB).";
                    break;
                case 'upload':
                    echo '❌ An error occurred during file upload.';
                    break;
                case 'pseudo':
                    echo '❌ This username is already taken. Please choose another one.';
                    break;
                default:
                    echo '❌ An unknown error occurred.';
            }
            ?>
        </div>
    <?php endif; ?>

    <!-- Formulaire de modification du profil, caché par défaut -->
    <div id="profile-edit" class="profile-edit" style="display: none;">
        <h3>Edit Your Profile</h3>
        <form action="index.php?page=profil&action=update" method="post" enctype="multipart/form-data" class="profile-form">

            <!-- Champ de saisie du pseudo -->
            <div class="form-group">
                <label for="pseudo">Username</label>
                <input type="text" name="pseudo" id="pseudo" value="<?= htmlspecialchars($user['pseudo']) ?>" required>
            </div>

            <!-- Zone de texte pour la biographie -->
            <div class="form-group">
                <label for="bio">Biography:</label>
                <textarea name="bio" id="bio" rows="5"><?php echo isset($_SESSION['user']['bio']) ? htmlspecialchars($_SESSION['user']['bio']) : ''; ?></textarea>
            </div>

            <!-- Champs pour les liens Instagram, X et Telegram -->
            <div class="form-group">
                <label for="instagram">Instagram Link</label>
                <input type="text" name="instagram" id="instagram" placeholder="https://instagram.com/yourprofile" 
                       value="<?= htmlspecialchars($user['instagram'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="x">X Link (formerly Twitter)</label>
                <input type="text" name="x" id="x" placeholder="https://x.com/yourprofile" 
                       value="<?= htmlspecialchars($user['x'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="telegram">Telegram Username</label>
                <input type="text" name="telegram" id="telegram" placeholder="@yourusername" 
                       value="<?= htmlspecialchars($user['telegram'] ?? '') ?>">
            </div>

            <!-- Upload d'une nouvelle image de profil -->
            <div class="form-group">
                <label for="image_profil">Change Profile Image:</label>
                <input type="file" name="image_profil" id="image_profil" accept="image/*">
            </div>

            <!-- Zone d’aperçu de la nouvelle image -->
            <div id="preview-container" class="preview-container">
                <img id="preview-image" src="#" alt="Image Preview" style="display: none;">
            </div>

            <!-- Affichage de l’ancienne image si elle existe -->
            <?php if (isset($_SESSION['user']['image_profil']) && !empty($_SESSION['user']['image_profil'])): ?>
                <div class="current-image" id="current-image">
                    <img src="<?php echo htmlspecialchars($_SESSION['user']['image_profil']); ?>" alt="Current Profile Image">
                </div>
            <?php endif; ?>

            <!-- Boutons d’action : sauvegarde, annulation, suppression du compte -->
            <div class="form-actions">
                <button type="submit" class="btn-submit">Save Changes</button>
                <button type="button" id="btn-cancel" class="btn-cancel">Cancel</button>
                <p id="btn-supprimer-init" class="btn-supprimer">Delete My Account</p>

                <!-- Confirmation de suppression, affichée après clic -->
                <a id="confirmation-suppression" style="display: none; margin-top: 10px;" href="index.php?page=profil&action=supprimer" class="btn-supprimer">
                    ✅ Yes, I want to delete my account
                </a>
                <p id="btn-annuler-suppression" class="btn-supprimer-return" style="display: none; margin-top: 8px;">Cancel</p>
            </div>
        </form>
    </div>
</div>

<!-- Chargement du script gérant l’interactivité du formulaire de profil -->
<script src="<?php echo RACINE_URL; ?>public/js/profil.js"></script>

<?php
// Inclusion du pied de page commun
require_once RACINE . 'app/views/templates/footer.php';
?>
