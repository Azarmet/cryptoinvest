<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once RACINE . "app/views/templates/header.php"; 
?>

<h2>Mon Profil</h2>

<!-- Affichage des informations de profil -->
<div id="profile-display">
    <p><strong>Pseudo :</strong> <?php echo htmlspecialchars($_SESSION['user']['pseudo']); ?></p>
    <p><strong>Email :</strong> <?php echo htmlspecialchars($_SESSION['user']['email']); ?></p>
    <p><strong>Biographie :</strong> 
        <?php 
            echo isset($_SESSION['user']['bio']) && !empty($_SESSION['user']['bio']) 
                 ? htmlspecialchars($_SESSION['user']['bio']) 
                 : 'Aucune bio disponible.';
        ?>
    </p>
    <?php if(isset($_SESSION['user']['image_profil']) && !empty($_SESSION['user']['image_profil'])): ?>
        <div>
            <img src="<?php echo htmlspecialchars($_SESSION['user']['image_profil']); ?>" alt="Image de Profil" width="150">
        </div>
    <?php endif; ?>
    <button id="btn-modify">Modifier Profil</button>
</div>

<!-- Formulaire de modification, initialement caché -->
<div id="profile-edit" style="display: none;">
    <h3>Modifier votre profil</h3>
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
        <button type="submit">Enregistrer les modifications</button>
        <button type="button" id="btn-cancel">Annuler</button>
    </form>
</div>

<script>
// Bouton pour afficher le formulaire de modification
document.getElementById('btn-modify').addEventListener('click', function(){
    document.getElementById('profile-display').style.display = 'none';
    document.getElementById('profile-edit').style.display = 'block';
});

// Bouton pour annuler la modification et revenir à l'affichage
document.getElementById('btn-cancel').addEventListener('click', function(){
    document.getElementById('profile-edit').style.display = 'none';
    document.getElementById('profile-display').style.display = 'block';
});
</script>

<?php require_once RACINE . "app/views/templates/footer.php"; ?>
