<?php require_once RACINE . 'app/views/templates/header.php'; ?>
<div class="register-container">
    <h2>Register</h2>
    <?php if (isset($error)): ?>
        <p class="error-msg"><?php echo $error; ?></p>
    <?php endif; ?>
    <form action="index.php?page=register&action=process" method="post" class="register-form">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="pseudo">Username:</label>
            <input type="text" name="pseudo" id="pseudo" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <!-- The area to display password strength criteria will be inserted here dynamically -->
        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
        </div>
        <button type="submit" class="btn-submit">Register</button>
    </form>
    <p class="login-link">Already registered? <a href="index.php?page=login">Log in</a></p>
</div>
<!-- Inclusion of the script for checking password criteria -->
<script src="<?php echo RACINE_URL; ?>public/js/passwordCriteria.js"></script>
<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
