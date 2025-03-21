<?php

function routeur() {
    // Déterminer quelle page/contrôleur appeler
    // Exemple : index.php?page=home ou index.php?page=faq
    $page = isset($_GET["page"]) ? $_GET["page"] : "home";

    switch($page) {
        case "home":
            require_once RACINE . "app/controllers/HomeController.php";
            break;
        
        case "faq":
            require_once RACINE . "app/controllers/FaqController.php";
            break;

        case "learn":
            require_once RACINE . "app/controllers/LearnController.php";
            break;
        
        case "dashboard":
            require_once RACINE . "app/controllers/DashboardController.php";
            break;
        
        case "market":
            require_once RACINE . "app/controllers/MarketController.php";
            break;
    
        case "login":
            require_once RACINE . "app/controllers/LoginController.php";
            break;
    
        case "leaderboard":
            require_once RACINE . "app/controllers/LeaderboardController.php";
            break;

        default:
            // Page 404 ou redirection
            echo "Erreur 404 : page introuvable.";
            break;
    }
}
?>