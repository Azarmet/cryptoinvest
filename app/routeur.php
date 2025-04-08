<?php
function routeur()
{
    $page = isset($_GET['page']) ? $_GET['page'] : 'home';

    switch ($page) {
        case 'home':
            require_once RACINE . 'app/controllers/HomeController.php';
            \App\Controllers\showHome();
            break;

        case 'faq':
            require_once RACINE . 'app/controllers/FaqController.php';
            $action = isset($_GET['action']) ? $_GET['action'] : 'show';
            if ($action == 'search') {
                \App\Controllers\searchFaq();
            } else {
                \App\Controllers\showFaq();
            }
            break;

        case 'dashboard':
            require_once RACINE . 'app/controllers/DashboardController.php';
            require_once RACINE . 'app/controllers/PortefeuilleController.php';
            require_once RACINE . 'app/controllers/PositionsController.php';
            $action = isset($_GET['action']) ? $_GET['action'] : 'show';
            switch ($action) {
                case 'show':
                    \App\Controllers\showDashboard();
                    break;
                case 'closePosition':
                    \App\Controllers\closePosition();
                    break;
                case 'refreshPositions':
                    \App\Controllers\refreshPositions();
                    break;
                case 'refreshPortfolioData':
                    \App\Controllers\refreshPortfolioData();
                    break;
                case 'getStats':
                    \App\Controllers\getStats();
                    break;
                    
                default:
                    // 404 ou redirection
                    echo 'Erreur 404 : action inconnue.';
            }
            break;

            case 'leaderboard':
                require_once RACINE . 'app/controllers/LeaderboardController.php';
                $action = $_GET['action'] ?? 'show';
                if ($action === 'search') {
                    \App\Controllers\search_user();
                } else {
                    \App\Controllers\showLeaderboard();
                }
                break;
            
            
        case 'profilboard':
            require_once RACINE . 'app/controllers/ProfilController.php';
            // On regarde s'il y a 'action' dans l'URL
            $pseudo = $_GET['pseudo'] ?? '';
            $action = isset($_GET['action']) ? $_GET['action'] : 'show';
            switch ($action) {
                case 'show':
                    \App\Controllers\showProfileByPseudo($pseudo);
                    break;
                case 'refreshPortfolioData':
                    \App\Controllers\refreshPortfolioDataPseudo($pseudo);
                    break;
                case 'getStats':
                    \App\Controllers\getStats($pseudo);
                    break;
                }
            break;

        case 'article':
            require_once RACINE . 'app/controllers/LearnController.php';
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

        case 'learn':
            require_once RACINE . 'app/controllers/LearnController.php';
            $action = isset($_GET['action']) ? $_GET['action'] : 'show';
            if ($action == 'search') {
                \App\Controllers\searchLearn();
            } else {
                \App\Controllers\showLearn();
            }
            break;

        case 'market':
            require_once RACINE . 'app/controllers/MarketController.php';
            require_once RACINE . 'app/controllers/PositionsController.php';
            require_once RACINE . 'app/controllers/PortefeuilleController.php';
            $action = isset($_GET['action']) ? $_GET['action'] : 'show';
            switch ($action) {
                case 'refresh': 
                    \App\Controllers\refreshMarket();
                    \App\Controllers\refreshPositions();
                break;
                case 'available-balance':
                    \App\Controllers\refreshPortfolioData();
                    break;
                case 'show':
                    \App\Controllers\showMarket();
                    break;
                case 'openPosition':
                    \App\Controllers\openPosition();
                    break;
                case 'closePosition':
                    \App\Controllers\closePosition();
                    break;
                case 'refreshPositions':
                    \App\Controllers\refreshPositions();
                    break;
                 default:
                    // 404 ou redirection
                    echo 'Erreur 404 : action inconnue.';
            }
            break;

        case 'profil':
            require_once RACINE . 'app/controllers/ProfilController.php';
            $action = isset($_GET['action']) ? $_GET['action'] : 'show';
            if ($action == 'update') {
                \App\Controllers\updateProfile();
            } else {
                \App\Controllers\showProfile();
            }
            break;

        case 'login':
            require_once RACINE . 'app/controllers/LoginController.php';
            $action = isset($_GET['action']) ? $_GET['action'] : 'show';
            if ($action == 'process') {
                \App\Controllers\processLogin();
            } else {
                \App\Controllers\showLogin();
            }
            break;

        case 'register':
            require_once RACINE . 'app/controllers/LoginController.php';
            $action = isset($_GET['action']) ? $_GET['action'] : 'show';
            if ($action == 'process') {
                \App\Controllers\processRegister();
            } else {
                // Affichage direct de la vue d'inscription
                require_once RACINE . 'app/views/register.php';
            }
            break;

        case 'watchlist':
            require_once RACINE . 'app/controllers/WatchlistController.php';
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

        case 'logout':
            require_once RACINE . 'app/controllers/ProfilController.php';
            \App\Controllers\logout();
            break;

        default:
            require_once RACINE . 'app/controllers/HomeController.php';
            \App\Controllers\showHome();
        break;
            
    }
}

function routeurBack()
{
    $pageback = isset($_GET['pageback']) ? $_GET['pageback'] : 'home';
    switch ($pageback) {
        case 'home':
            require_once RACINE . 'app/controllers/HomeController.php';
            \App\Controllers\showBackHome();
            break;

        case 'faq':
            require_once RACINE . 'app/controllers/FaqController.php';
            \App\Controllers\showBackFaq();
            break;

        case 'createFaq':
            require_once RACINE . 'app/controllers/FaqController.php';
            \App\Controllers\createFaq();
            break;

        case 'editFaq':
            if (isset($_GET['id'])) {
                require_once RACINE . 'app/controllers/FaqController.php';
                \App\Controllers\editFaq($_GET['id']);
            } else {
                echo "ID manquant pour l'édition de la FAQ.";
            }
            break;

        case 'deleteFaq':
            if (isset($_GET['id'])) {
                require_once RACINE . 'app/controllers/FaqController.php';
                \App\Controllers\deleteFaq($_GET['id']);
            } else {
                echo 'ID manquant pour la suppression de la FAQ.';
            }
            break;

        case 'learn':
            require_once RACINE . 'app/controllers/LearnController.php';
            \App\Controllers\showBackLearn();
            break;

        case 'createArticle':
            require_once RACINE . 'app/controllers/LearnController.php';
            \App\Controllers\createArticle();
            break;

        case 'editArticle':
            if (isset($_GET['id'])) {
                require_once RACINE . 'app/controllers/LearnController.php';
                \App\Controllers\editArticle($_GET['id']);
            } else {
                echo "ID manquant pour l'édition.";
            }
            break;

        case 'deleteArticle':
            if (isset($_GET['id'])) {
                require_once RACINE . 'app/controllers/LearnController.php';
                \App\Controllers\deleteArticle($_GET['id']);
            } else {
                echo 'ID manquant pour la suppression.';
            }
            break;

        case 'market':
            require_once RACINE . 'app/controllers/MarketController.php';
            \App\Controllers\showBackMarket();
            break;
        case 'createCryptoMarket':
            require_once RACINE . 'app/controllers/MarketController.php';
            \App\Controllers\createCryptoMarket();
            break;

        case 'deleteCryptoMarket':
            if (isset($_GET['id'])) {
                require_once RACINE . 'app/controllers/MarketController.php';
                \App\Controllers\deleteCryptoMarket($_GET['id']);
            } else {
                echo 'ID manquant pour la suppression de la crypto du marché.';
            }
            break;

        case 'createCryptoTrans':
            require_once RACINE . 'app/controllers/MarketController.php';
            \App\Controllers\createCryptoTrans();
            break;

        case 'deleteCryptoTrans':
            if (isset($_GET['id'])) {
                require_once RACINE . 'app/controllers/MarketController.php';
                \App\Controllers\deleteCryptoTrans($_GET['id']);
            } else {
                echo 'ID manquant pour la suppression de la crypto transactionnelle.';
            }
            break;

        case 'logout':
            require_once RACINE . 'app/controllers/ProfilController.php';
            \App\Controllers\logout();
            break;

        case 'users':
            require_once RACINE . 'app/controllers/UserController.php';
            \App\Controllers\showBackUsers();
            break;

        case 'deleteUser':
            if (isset($_GET['id'])) {
                require_once RACINE . 'app/controllers/UserController.php';
                \App\Controllers\deleteUser($_GET['id']);
            } else {
                echo "ID manquant pour la suppression de l'utilisateur.";
            }
            break;

        case 'toggleUserRole':
            if (isset($_GET['id'])) {
                require_once RACINE . 'app/controllers/UserController.php';
                \App\Controllers\toggleUserRole($_GET['id']);
            } else {
                echo 'ID manquant pour la modification du rôle.';
            }
            break;

        case 'searchUser':
            require_once RACINE . 'app/controllers/UserController.php';
            \App\Controllers\searchUser();
            break;

        default:
            echo 'Erreur 404 : page introuvable.';
            break;
    }
}
?>
