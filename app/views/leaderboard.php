<?php require_once RACINE . 'app/views/templates/header.php'; ?>

<div class="leaderboard-container">
    <h1>Leaderboard</h1>
    <?php $tableRows = ''; ?>
    <?php foreach ($usersWithSolde as $user): 
        $photoProfil = "<img src=\"{$user['image']}\" alt=\"Profil\" width=\"25\">";
        $pseudo = htmlspecialchars($user['pseudo']);
        $solde = number_format($user['solde'], 2, ',', ' ');
        $tableRows .= "
            <tr>
                <td><a href=\"index.php?page=profilboard&pseudo=$pseudo\">$photoProfil</a></td>
                <td>$pseudo</td>
                <td>$solde</td>
            </tr>
        ";
    endforeach; ?>
    
    <div class="table-responsive">
        <table class="leaderboard-table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Pseudo</th>
                    <th>Solde (€)</th>
                </tr>
            </thead>
            <tbody>
                <?= $tableRows ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
