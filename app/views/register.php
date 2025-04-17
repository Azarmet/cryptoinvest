<?php
// Inclusion de l'en-tête commun (logo, navigation, CSS, etc.)
require_once RACINE . 'app/views/templates/header.php';
?>
<div class="register-container">
    <!-- Titre de la page d'inscription -->
    <h2>Register</h2>

    <!-- Affichage d'un éventuel message d'erreur -->
    <?php if (isset($error)): ?>
        <p class="error-msg"><?php echo $error; ?></p>
    <?php endif; ?>

    <!-- Formulaire d'inscription -->
    <form action="index.php?page=register&action=process" method="post" class="register-form">
        <!-- Champ pour l'adresse email -->
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>

        <!-- Champ pour le pseudo -->
        <div class="form-group">
            <label for="pseudo">Username:</label>
            <input type="text" name="pseudo" id="pseudo" required>
        </div>

        <!-- Champ pour le mot de passe -->
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>

        <!-- Zone réservée à l'affichage dynamique des critères de sécurité du mot de passe -->
        <!-- Elle sera remplie par passwordCriteria.js -->
        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
        </div>

        <!-- Bouton de validation du formulaire -->
        <button type="submit" class="btn-submit">Register</button>
    </form>

    <!-- Lien vers la page de connexion si déjà inscrit -->
    <p class="login-link">Already registered? <a href="index.php?page=login">Log in</a></p>
</div>

<!-- Chargement du script gérant l'affichage des critères de mot de passe -->
<script src="<?php echo RACINE_URL; ?>public/js/passwordCriteria.js"></script>

<?php
// Inclusion du pied de page commun (footer, scripts supplémentaires, etc.)
require_once RACINE . 'app/views/templates/footer.php';
?>
