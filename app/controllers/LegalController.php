<?php
namespace App\Controllers;

function showMentions(){
    require_once RACINE . 'app/views/mentionsLegales.php';

}

function showConfidential(){
    require_once RACINE . 'app/views/confidentialite.php';
}


?>