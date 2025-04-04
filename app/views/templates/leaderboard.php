<link rel="stylesheet" href="<?= RACINE_URL . 'public/css/leaderboard.css'?>">

<div class="leaderboard-container">
    
    
    
<?php
    $tableRows = '';
    $page = $_GET['page'];
    if ($page === 'home'): ?>
    <h1>Leaderboard Top 3</h1>
    <?php else:?>
    <h1>Leaderboard</h1>         
    <?php endif;
    foreach ($usersWithSolde as $user):
        $photoProfil = "<img src=\"{$user['image']}\" alt=\"Profil\" width=\"25\">";
        $pseudo = htmlspecialchars($user['pseudo']);
        $solde = number_format($user['solde'], 2, ',', ' ');
        $lienProfil = "index.php?page=profilboard&pseudo=$pseudo";
        $tableRows .= "
        <tr onclick=\"window.location.href='{$lienProfil}';\" style=\"cursor:pointer;\">
            <td>$photoProfil</td>
            <td>$pseudo</td>
            <td>$solde</td>
        </tr>
    ";
    endforeach;
    ?>
    
    <div class="table-responsive">
        <table class="leaderboard-table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Pseudo</th>
                    <th>Solde ($)</th>
                </tr>
            </thead>
            <tbody>
                <?= $tableRows ?>
            </tbody>
        </table>
    </div>
    <?php if ($page === 'home'): ?>
    <a href="index.php?page=leaderboard" class="btn-go-market">View Learderboard</a>       
    <?php endif;?>
    
</div>