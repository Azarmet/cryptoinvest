<?php
function routeur() {
    $page = isset($_GET["page"]) ? $_GET["page"] : "home";

    switch($page) {
        case "home":
            require_once RACINE . "app/controllers/HomeController.php";
            \App\Controllers\showHome();
            break;
        
        case "faq":
            require_once RACINE . "app/controllers/FaqController.php";
            \App\Controllers\showFaq();
            break;

        case "articles":
            require_once RACINE . "app/controllers/ArticleController.php";
            \App\Controllers\showArticles();
            break;

        case "dashboard":
            require_once RACINE . "app/controllers/DashboardController.php";
            \App\Controllers\showDashboard();
            break;

        case "leaderboard":
            require_once RACINE . "app/controllers/LeaderboardController.php";
            \App\Controllers\showLeaderboard();
            break;

        case "learn":
            require_once RACINE . "app/controllers/LearnController.php";
            \App\Controllers\showLearn();
            break;

        case "market":
             require_once RACINE . "app/controllers/MarketController.php";
            $action = isset($_GET['action']) ? $_GET['action'] : 'show';
            if ($action == 'refresh') {
                \App\Controllers\refreshMarket();
            } else {
                \App\Controllers\showMarket();
            }
            break;

        case "profil":
            require_once RACINE . "app/controllers/ProfilController.php";
            \App\Controllers\showProfil();
            break;

        case "login":
            require_once RACINE . "app/controllers/LoginController.php";
            $action = isset($_GET['action']) ? $_GET['action'] : 'show';
            if ($action == 'process') {
                \App\Controllers\processLogin();
            } else {
                \App\Controllers\showLogin();
            }
            break;

        case "register":
            require_once RACINE . "app/controllers/LoginController.php";
            $action = isset($_GET['action']) ? $_GET['action'] : 'show';
            if ($action == 'process') {
                \App\Controllers\processRegister();
            } else {
                // Affichage direct de la vue d'inscription
                require_once RACINE . "app/views/register.php";
            }
            break;
        
        case "logout":
            require_once RACINE . "app/controllers/ProfilController.php";
            \App\Controllers\logout();
            break;
            

        default:
            echo "Erreur 404 : page introuvable.";
            break;
    }
}
?>
