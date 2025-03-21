<?php require_once RACINE . "app/views/templates/header.php"; ?>
<h2>Inscription</h2>
<?php if (isset($error)): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>
<form action="index.php?page=register&action=process" method="post">
    <label for="email">Email :</label>
    <input type="email" name="email" required>
    <br>
    <label for="pseudo">Pseudo :</label>
    <input type="text" name="pseudo" required>
    <br>
    <label for="password">Mot de passe :</label>
    <input type="password" name="password" required>
    <br>
    <label for="confirm_password">Confirmer le mot de passe :</label>
    <input type="password" name="confirm_password" required>
    <br>
    <button type="submit">S'inscrire</button>
</form>
<p>Déjà inscrit ? <a href="index.php?page=login">Se connecter</a></p>
<?php require_once RACINE . "app/views/templates/footer.php"; ?>
