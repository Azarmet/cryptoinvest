<?php require_once RACINE . 'app/views/templates/header.php'; ?>
<link rel="stylesheet" href="<?= RACINE_URL . 'public/css/register.css'?>">
<div class="register-container">
    <h2>Inscription</h2>
    <?php if (isset($error)): ?>
        <p class="error-msg"><?php echo $error; ?></p>
    <?php endif; ?>
    <form action="index.php?page=register&action=process" method="post" class="register-form">
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="pseudo">Pseudo :</label>
            <input type="text" name="pseudo" id="pseudo" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" name="password" id="password" required>
        </div>
        <!-- La zone pour afficher les critères de sécurité sera insérée ici dynamiquement -->
        <div class="form-group">
            <label for="confirm_password">Confirmer le mot de passe :</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
        </div>
        <button type="submit" class="btn-submit">S'inscrire</button>
    </form>
    <p class="login-link">Déjà inscrit ? <a href="index.php?page=login">Se connecter</a></p>
</div>
<!-- Inclusion du script pour la vérification des critères de mot de passe -->
<script src="<?php echo RACINE_URL; ?>public/js/passwordCriteria.js"></script>
<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
