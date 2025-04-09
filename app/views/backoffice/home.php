<?php require_once RACINE . 'app/views/backoffice/headerback.php'; ?>

<section class="backoffice-home">
    <h1 class="dashboard-title">Accueil Back Office</h1>

    <div class="dashboard-cards">
        <a href="index.php?pageback=faq" class="dashboard-card">
            <h2>Gérer la FAQ</h2>
            <p>Ajoutez, modifiez ou supprimez les questions fréquentes.</p>
        </a>

        <a href="index.php?pageback=learn" class="dashboard-card">
            <h2>Gestion Learn</h2>
            <p>Organisez vos articles éducatifs pour les utilisateurs.</p>
        </a>

        <a href="index.php?pageback=market" class="dashboard-card">
            <h2>Gestion du Market</h2>
            <p>Supervisez les données des cryptos en temps réel.</p>
        </a>

        <a href="index.php?pageback=users" class="dashboard-card">
            <h2>Utilisateurs</h2>
            <p>Consultez et gérez les profils des membres.</p>
        </a>
    </div>
</section>

<?php require_once RACINE . 'app/views/backoffice/footerback.php'; ?>
