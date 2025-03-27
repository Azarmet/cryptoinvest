<?php
namespace App\Controllers;

use App\Models\User;

function showHome() {
    require_once RACINE . "app/views/home.php";
}
function showBackHome() {
    require_once RACINE . "app/views/backoffice/home.php";
}



?>