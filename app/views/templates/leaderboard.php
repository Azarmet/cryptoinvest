
<div class="leaderboard-container">

<?php
$tableRows = '';
if (isset($_GET['page'])):
    $page = $_GET['page'];
    if ($page === 'home'):
        ?>
        <h1>Leaderboard <span class="orange">Top 3</span></h1>
    <?php else: ?>
        <h1>Leader<span class="orange">board</span></h1>   
        <div class="search-container">
            <input type="text" class="search-input" id="search-input" placeholder="Search for a user...">
        </div>  
    <?php
    endif;
endif;
$rank = 0;
foreach ($usersWithSolde as $user):
    $photoProfilDesktop = "<img src=\"{$user['image']}\" alt=\"Profile\" width=\"36\" class=\"desktop-img\">";
    $photoProfilMobile  = "<img src=\"{$user['image']}\" alt=\"Profile\" width=\"36\" class=\"mobile-img\">";
    
    $pseudo = htmlspecialchars($user['pseudo']);
    

    $solde = number_format($user['solde'], 0, ',', ' ');
    $lienProfil = "index.php?page=profilboard&pseudo=$pseudo";
    $rank += 1;
    $trophee = '';

    if ($rank === 1) {
        $trophee = ' 🥇';
    } elseif ($rank === 2) {
        $trophee = ' 🥈';
    } elseif ($rank === 3) {
        $trophee = ' 🥉';
    }
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
    if (isset($_GET['page']) && $page !== 'home') {
        $pnl24hVal = $user['pnl_24h'];
        $pnl7jVal = $user['pnl_7j'];

        $pnl24h = number_format($pnl24hVal, 0, ',', ' ');
        $pnl7j = number_format($pnl7jVal, 0, ',', ' ');

        $pnl24hClass = $pnl24hVal >= 0 ? 'positive' : 'negative';
        $pnl7jClass = $pnl7jVal >= 0 ? 'positive' : 'negative';

        $tableRows .= "
        <td class=\"$pnl24hClass\">$pnl24h $</td>
        <td class=\"$pnl7jClass\">$pnl7j $</td>";
    }

    $tableRows .= "</tr>";
endforeach;
?>

<div class="table-responsive">
    <table class="leaderboard-table">
    <thead>
    <tr>
        <th>RANK</th>
        <th class="th-pseudo">Username</th>
        <?php if (isset($_GET['page']) && $page === 'home'): ?>
        <th>Balance </th>
        <?php endif; ?>
        <!-- Added the "sortable" class with a data-sort attribute to identify the column -->
        
        <?php if (isset($_GET['page']) && $page !== 'home'): ?>
            <th class="sortable active" data-sort="solde" data-order="desc">Balance </th>
            <th class="sortable" data-sort="pnl_24h">PnL 24h </th>
            <th class="sortable" data-sort="pnl_7j">PnL 7d </th>
        <?php endif; ?>
    </tr>
</thead>

        <tbody>
            <?= $tableRows ?>
        </tbody>
    </table>
</div>

<?php
if (!isset($page)):
    $page = 'home';
endif;
?>

<?php if ($page === 'home'): ?>
    <a href="index.php?page=leaderboard" class="btn-go-market">View Leaderboard</a>       
<?php endif; ?>

</div>
<script src="<?php echo RACINE_URL; ?>public/js/leaderboard.js"></script>
