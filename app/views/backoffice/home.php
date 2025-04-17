<?php 
// Inclusion de l’en‑tête commun du back‑office (logo, navigation, styles, etc.)
require_once RACINE . 'app/views/backoffice/headerback.php'; 
?>

<!-- Section principale de la page d’accueil du back‑office -->
<section class="backoffice-home">
    <!-- Titre de la page -->
    <h1 class="dashboard-title">Back Office Home</h1>

    <!-- Conteneur des cartes de navigation rapide vers les différentes sections -->
    <div class="dashboard-cards">
        <!-- Carte FAQ : gestion des questions fréquentes -->
        <a href="index.php?pageback=faq" class="dashboard-card">
            <h2>Manage FAQ</h2>
            <p>Add, modify or delete frequently asked questions.</p>
        </a>

        <!-- Carte Learn : gestion des articles pédagogiques -->
        <a href="index.php?pageback=learn" class="dashboard-card">
            <h2>Learn Management</h2>
            <p>Organize your educational articles for users.</p>
        </a>

        <!-- Carte Market : supervision et mise à jour des données de marché -->
        <a href="index.php?pageback=market" class="dashboard-card">
            <h2>Market Management</h2>
            <p>Supervise real-time crypto data.</p>
        </a>

        <!-- Carte Users : visualisation et gestion des profils utilisateur -->
        <a href="index.php?pageback=users" class="dashboard-card">
            <h2>Users</h2>
            <p>View and manage member profiles.</p>
        </a>
    </div>
</section>

<?php 
// Inclusion du pied de page commun du back‑office (mentions légales, déconnexion, etc.)
require_once RACINE . 'app/views/backoffice/footerback.php'; 
?>
