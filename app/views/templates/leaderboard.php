<div class="leaderboard-container">

<?php
// Initialisation de la variable qui contiendra les lignes du tableau
$tableRows = '';

// Si un paramètre "page" est passé en GET, on le récupère
if (isset($_GET['page'])):
    $page = $_GET['page'];

    // Selon la valeur de la page, on affiche un titre adapté
    if ($page === 'home'):
        ?>
        <!-- Titre pour la page d'accueil (Top 3) -->
        <h1>Leaderboard <span class="orange">Top 3</span></h1>
    <?php else: ?>
        <!-- Titre générique avec barre de recherche pour les autres pages -->
        <h1>Leader<span class="orange">board</span></h1>   
        <div class="search-container">
            <input type="text" class="search-input" id="search-input" placeholder="Search for a user...">
        </div>  
    <?php
    endif;
endif;

// Compteur de rang, commence à zéro
$rank = 0;

// Parcours de chaque utilisateur avec solde
foreach ($usersWithSolde as $user):
    // Construction des balises <img> pour affichage desktop et mobile
    $photoProfilDesktop = "<img src=\"{$user['image']}\" alt=\"Profile\" width=\"36\" class=\"desktop-img\">";
    $photoProfilMobile  = "<img src=\"{$user['image']}\" alt=\"Profile\" width=\"36\" class=\"mobile-img\">";
    
    // Sécurisation du pseudo en échappant les caractères HTML
    $pseudo = htmlspecialchars($user['pseudo']);

    // Formatage du solde avec séparateur de milliers
    $solde = number_format($user['solde'], 0, ',', ' ');

    // Lien vers la page de profil détaillé
    $lienProfil = "index.php?page=profilboard&pseudo=$pseudo";

    // Incrément du rang
    $rank += 1;

    // Attribution d'un trophée pour les trois premiers
    $trophee = '';
    if ($rank === 1) {
        $trophee = ' 🥇';
    } elseif ($rank === 2) {
        $trophee = ' 🥈';
    } elseif ($rank === 3) {
        $trophee = ' 🥉';
    }

    // Construction de la ligne de tableau avec redirection au clic
    $tableRows .= "
    <tr onclick=\"window.location.href='{$lienProfil}';\" style=\"cursor:pointer;\">
        <td class=\"rank-$rank\">$trophee$rank</td>
        <td class=\"td-pseudo\">
            $photoProfilDesktop 
            <span class=\"pseudo-text\">$pseudo</span>
        </td>
        <td class=\"td-solde\">
            $photoProfilMobile 
            $solde $
        </td>";

    // Si on n'est pas sur la page "home", on ajoute les colonnes PnL 24h et PnL 7j
    if (isset($_GET['page']) && $page !== 'home') {
        $pnl24hVal = $user['pnl_24h'];
        $pnl7jVal = $user['pnl_7j'];

        // Formatage des valeurs avec séparateur de milliers
        $pnl24h = number_format($pnl24hVal, 0, ',', ' ');
        $pnl7j = number_format($pnl7jVal, 0, ',', ' ');

        // Détermination de la classe CSS selon la positivité ou négativité
        $pnl24hClass = $pnl24hVal >= 0 ? 'positive' : 'negative';
        $pnl7jClass = $pnl7jVal >= 0 ? 'positive' : 'negative';

        $tableRows .= "
        <td class=\"$pnl24hClass\">$pnl24h $</td>
        <td class=\"$pnl7jClass\">$pnl7j $</td>";
    }

    // Fermeture de la ligne
    $tableRows .= "</tr>";
endforeach;
?>

<!-- Conteneur responsive pour le tableau -->
<div class="table-responsive">
    <table class="leaderboard-table">
        <thead>
            <tr>
                <th>RANK</th>
                <th class="th-pseudo">Username</th>
                <?php if (isset($_GET['page']) && $page === 'home'): ?>
                    <!-- Colonne Balance uniquement sur l'accueil -->
                    <th>Balance</th>
                <?php endif; ?>

                <!-- Colonnes triables uniquement hors accueil -->
                <?php if (isset($_GET['page']) && $page !== 'home'): ?>
                    <th class="sortable active" data-sort="solde" data-order="desc">Balance</th>
                    <th class="sortable" data-sort="pnl_24h">PnL 24h</th>
                    <th class="sortable" data-sort="pnl_7j">PnL 7d</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <!-- Insertion des lignes construites dynamiquement -->
            <?= $tableRows ?>
        </tbody>
    </table>
</div>

<?php
// Par défaut, si $page n'est pas défini, on considère "home"
if (!isset($page)):
    $page = 'home';
endif;
?>

<?php if ($page === 'home'): ?>
    <!-- Lien pour accéder à la page complète du leaderboard -->
    <a href="index.php?page=leaderboard" class="btn-go-market">View Leaderboard</a>
<?php endif; ?>

</div>

<!-- Inclusion du script JavaScript gérant la recherche et le tri -->
<script src="<?php echo RACINE_URL; ?>public/js/leaderboard.js"></script>
