<?php 
// Assurez-vous que la session est démarrée (si ce n'est pas déjà fait dans le header)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once RACINE . "app/views/templates/header.php"; 
?>

<h2>Mon Profil</h2>

<?php if(isset($_GET['success'])): ?>
    <p style="color: green;">Profil mis à jour avec succès.</p>
<?php elseif(isset($_GET['error'])): ?>
    <p style="color: red;">Une erreur est survenue lors de la mise à jour.</p>
<?php endif; ?>

<form action="index.php?page=profil&action=update" method="post" enctype="multipart/form-data">
    <div>
        <label for="bio">Biographie :</label><br>
        <textarea name="bio" id="bio" rows="5" cols="50"><?php echo isset($_SESSION['user']['bio']) ? htmlspecialchars($_SESSION['user']['bio']) : ''; ?></textarea>
    </div>
    <div>
        <label for="image_profil">Image de Profil :</label><br>
        <input type="file" name="image_profil" id="image_profil">
        <?php if(isset($_SESSION['user']['image_profil']) && !empty($_SESSION['user']['image_profil'])): ?>
            <div>
                <img src="<?php echo htmlspecialchars($_SESSION['user']['image_profil']); ?>" alt="Image de Profil" width="100">
            </div>
        <?php endif; ?>
    </div>
    <button type="submit">Mettre à jour</button>
</form>

<?php require_once RACINE . "app/views/templates/footer.php"; ?>
