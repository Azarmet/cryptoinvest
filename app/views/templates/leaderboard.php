<div class="leaderboard-container">
    <h1>Leaderboard</h1>
    <?php $tableRows = ''; ?>
    
<?php if (isset($_SESSION['user'])): ?>
                    
    <?php
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

        <?php else: ?>

            <?php
            foreach ($usersWithSolde as $user):
                $photoProfil = "<img src=\"{$user['image']}\" alt=\"Profil\" width=\"25\">";
                $pseudo = htmlspecialchars($user['pseudo']);
                $solde = number_format($user['solde'], 2, ',', ' ');
                $tableRows .= "
        <tr>
            <td>$photoProfil</td>
            <td>$pseudo</td>
            <td>$solde</td>
        </tr>
    ";
            endforeach;
            ?>

        <?php endif; ?>
    
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