<?php require_once RACINE . 'app/views/templates/header.php'; ?>
<h1>Leaderboard</h1>
<?php $tableRows = '';

foreach ($usersWithSolde as $user) {
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
} ?>

 
    <table border="1" cellspacing="0" cellpadding="8">
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

 
<?php require_once RACINE . 'app/views/templates/footer.php'; ?>