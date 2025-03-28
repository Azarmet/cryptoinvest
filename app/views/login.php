<?php require_once RACINE . 'app/views/templates/header.php'; ?>
<h2>Connexion</h2>
<?php if (isset($error)): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>
<form action="index.php?page=login&action=process" method="post">
    <label for="email">Email :</label>
    <input type="email" name="email" required>
    <br>
    <label for="password">Mot de passe :</label>
    <input type="password" name="password" required>
    <br>
    <button type="submit">Se connecter</button>
</form>
<p>Pas encore inscrit ? <a href="index.php?page=register">S'inscrire</a></p>
<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
