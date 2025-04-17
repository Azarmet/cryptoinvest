<?php
namespace App\Controllers;

/**
 * Contrôleur des pages d’informations légales et de confidentialité.
 */
 
/**
 * Affiche la page des mentions légales.
 *
 * - Inclut la vue app/views/mentionsLegales.php.
 */
function showMentions(){
    require_once RACINE . 'app/views/mentionsLegales.php';

}

/**
 * Affiche la page de la politique de confidentialité.
 *
 * - Inclut la vue app/views/confidentialite.php.
 */
function showConfidential(){
    require_once RACINE . 'app/views/confidentialite.php';
}


?>