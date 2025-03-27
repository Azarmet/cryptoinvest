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
            $action = isset($_GET['action']) ? $_GET['action'] : 'show';
            if ($action == 'search') {
                \App\Controllers\searchFaq();
            } else {
                \App\Controllers\showFaq();
            }
            break;

        case "articles":
            require_once RACINE . "app/controllers/ArticleController.php";
            \App\Controllers\showArticles();
            break;

        case "dashboard":
            require_once RACINE . "app/controllers/DashboardController.php";
            $action = isset($_GET['action']) ? $_GET['action'] : 'show';
            switch($action) {
                case "show":
                    \App\Controllers\showDashboard();
                    break;
                case "openPosition":
                    \App\Controllers\openPosition();
                    break;
                case "closePosition":
                    \App\Controllers\closePosition();
                    break;
                case "refreshPositions":
                    \App\Controllers\refreshPositions();
                    break;
                case "refreshPortfolioData":
                    \App\Controllers\refreshPortfolioData();
                    break;
                default:
                    // 404 ou redirection
                    echo "Erreur 404 : action inconnue.";
            }
            break;
            

        case "leaderboard":
            require_once RACINE . "app/controllers/LeaderboardController.php";
            \App\Controllers\showLeaderboard();
            break;

        case "profilboard":
            require_once RACINE . "app/controllers/ProfilController.php";
            // On regarde s'il y a 'action' dans l'URL
            $action = isset($_GET['action']) ? $_GET['action'] : 'show';
            $pseudo = $_GET['pseudo'] ?? '';
                if ($action === 'refreshPortfolioData') {
                // Renvoie du JSON seulement
                \App\Controllers\refreshPortfolioDataPseudo($pseudo);
                } else {
                // Affiche la page HTML
                \App\Controllers\showProfileByPseudo($pseudo);
                }
                break;
        
        case 'article':
            require_once RACINE ."app/controllers/LearnController.php";
            $action = isset($_GET['action']) ? $_GET['action'] : 'show';
                if ($action === 'show') {
                    $id = $_GET['id'] ?? null;
                    if ($id) {
                        \App\Controllers\showArticleDetail($id);
                    } else {
                        \App\Controllers\showLearn();
                    }
                }
            break;

        case "learn":
            require_once RACINE . "app/controllers/LearnController.php";
            $action = isset($_GET['action']) ? $_GET['action'] : 'show';
            if ($action == 'search') {
                \App\Controllers\searchLearn();
            } else {
                \App\Controllers\showLearn();
            }
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
            $action = isset($_GET['action']) ? $_GET['action'] : 'show';
            if ($action == 'update') {
                \App\Controllers\updateProfile();
            } else {
                \App\Controllers\showProfile();
            }
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

        case "watchlist":
             require_once RACINE . "app/controllers/WatchlistController.php";
             $action = isset($_GET['action']) ? $_GET['action'] : 'show';
             if ($action == 'add') {
                 \App\Controllers\addToWatchlist();
            } elseif ($action == 'remove') {
                 \App\Controllers\removeFromWatchlist();
              } elseif ($action == 'refresh') {
                 \App\Controllers\refreshWatchlist();
              } else {
                 \App\Controllers\showWatchlist();
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


function routeurBack(){
    $pageback = isset($_GET["pageback"]) ? $_GET["pageback"] : "home";
    switch($pageback) {

        case 'home':
            require_once RACINE ."app/controllers/HomeController.php";
            \App\Controllers\showBackHome();
            break;
            

        case 'faq':
            require_once RACINE ."app/controllers/FaqController.php";
            \App\Controllers\showBackFaq();
            break;

        case "learn":
            require_once RACINE . "app/controllers/LearnController.php";
            \App\Controllers\showBackLearn();
            break;
            
        case "createArticle":
            require_once RACINE . "app/controllers/LearnController.php";
            \App\Controllers\createArticle();
            break;
        
        case "editArticle":
            if (isset($_GET['id'])) {
                require_once RACINE . "app/controllers/LearnController.php";
                \App\Controllers\editArticle($_GET['id']);
            } else {
                echo "ID manquant pour l'édition.";
            }
            break;
            
        case "deleteArticle":
            if (isset($_GET['id'])) {
                require_once RACINE . "app/controllers/LearnController.php";
                \App\Controllers\deleteArticle($_GET['id']);
             } else {
                echo "ID manquant pour la suppression.";
            }
            break;
            
        case "market":
            require_once RACINE . "app/controllers/MarketController.php";
            \App\Controllers\showBackMarket();
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
