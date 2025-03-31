<?php require_once RACINE . 'app/views/templates/header.php'; ?>
<div class="login-container">
    <h2>Connexion</h2>
    <?php if (isset($error)): ?>
        <p class="error-msg"><?php echo $error; ?></p>
    <?php endif; ?>
    <form class="login-form" action="index.php?page=login&action=process" method="post">
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit" class="submit-btn">Se connecter</button>
    </form>
    <p class="register-link">Pas encore inscrit ? <a href="index.php?page=register">S'inscrire</a></p>
</div>
<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
