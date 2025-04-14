<?php require_once RACINE . 'app/views/templates/header.php'; ?>
<div class="login-container">
    <h2>Login</h2>
    <?php if (isset($error)): ?>
        <p class="error-msg"><?php echo $error; ?></p>
    <?php endif; ?>
    <form class="login-form" action="index.php?page=login&action=process" method="post">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit" class="submit-btn">Log In</button>
    </form>
    <p class="register-link">Not registered yet? <a href="index.php?page=register">Sign Up</a></p>
</div>
<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
