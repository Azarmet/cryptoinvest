<?php
namespace App\Controllers;

use App\Models\Article;


/**
 * Contrôleur gérant la section « Learn » (articles) du site.
 */
  
/**
 * Affiche la page « Learn » publique.
 *
 * - Récupère les filtres GET (catégorie, recherche) pour l'interface JS.
 * - Charge la vue app/views/learn.php.
 */

function showLearn()
{
    $categorie = isset($_GET['categorie']) ? $_GET['categorie'] : 'Tous';
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    require_once RACINE . 'app/views/learn.php';
}

/**
 * Affiche la liste des articles dans le back-office.
 *
 * - Récupère tous les articles (sans filtre).
 * - Charge la vue backoffice/app/views/backoffice/learn.php.
 */
function showBackLearn()
{
    $articleModel = new Article();
    $articles = $articleModel->getArticles();
    require_once RACINE . 'app/views/backoffice/learn.php';
}

/**
 * Affiche le détail d’un article.
 *
 * @param int $id Identifiant de l’article.
 */
function showArticleDetail($id)
{
    $articleModel = new \App\Models\Article();
    $article = $articleModel->getById($id);

    if ($article) {
        require_once RACINE . 'app/views/detailArticle.php';
    } else {
        echo 'Article introuvable.';
    }
}

/**
 * Crée un nouvel article via le back-office.
 *
 * - Gère l’upload d’image.
 * - Valide et préremplit le formulaire en cas d’erreur.
 * - Appelle Article::createArticle().
 */
function createArticle()
{
    $error = null;
    $article = [];  // utilisé pour préremplir le formulaire

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $imageName = changeImage($error);

        $article = [  // On stocke les valeurs déjà tapées par l'utilisateur
            'titre' => $_POST['titre'],
            'contenu' => $_POST['contenu'],
            'auteur' => $_SESSION['user']['id_utilisateur'],
            'categorie' => $_POST['categorie'],
            'statut' => $_POST['statut'],
            'image' => $imageName
        ];

        if (!$error) {
            $articleModel = new \App\Models\Article();
            $articleModel->createArticle($article);
            header('Location: index.php?pageback=learn');
            exit;
        }
    }

    require RACINE . 'app/views/backoffice/formArticle.php';
}


/**
 * Édite un article existant via le back-office.
 *
 * @param int $id Identifiant de l’article à modifier.
 */
function editArticle($id)
{
    $error = null;
    $articleModel = new \App\Models\Article();
    $article = $articleModel->getById($id);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $imageName = changeImage($error);
        if (!$imageName) {
            $imageName = $article['image'];  // garder l'image précédente
        }

        $article = [  // remplacer l'article avec les nouvelles valeurs
            'titre' => $_POST['titre'],
            'contenu' => $_POST['contenu'],
            'auteur' => $_SESSION['user']['id_utilisateur'],
            'categorie' => $_POST['categorie'],
            'statut' => $_POST['statut'],
            'image' => $imageName
        ];

        if (!$error) {
            $articleModel->updateArticle($id, $article);
            header('Location: index.php?pageback=learn');
            exit;
        }
    }

    require RACINE . 'app/views/backoffice/formArticle.php';
}


/**
 * Gère l’upload d’une image pour un article.
 *
 * - Vérifie la taille et le type MIME.
 * - Crée le dossier si nécessaire.
 * - Génère un nom unique et déplace le fichier.
 *
 * @param string|null &$error Retourne le message d’erreur en cas d’échec.
 * @return string|null Nom du fichier enregistré ou null.
 */
function changeImage(&$error = null)
{
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === 0) {
        $tmpName = $_FILES['image']['tmp_name'];
        $fileSize = $_FILES['image']['size'];
        $mimeType = mime_content_type($tmpName);
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 10 * 1024 * 1024;  // 10 Mo

        if ($fileSize > $maxSize) {
            $error = 'Le fichier dépasse la taille maximale autorisée (10 Mo).';
            return null;
        }

        if (!in_array($mimeType, $allowedTypes)) {
            $error = 'Type de fichier non autorisé. Seules les images JPEG, PNG, GIF ou WEBP sont acceptées.';
            return null;
        }

        $targetDir = 'public/uploads/article/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = uniqid() . '.' . $extension;
        $targetFile = $targetDir . $imageName;

        if (move_uploaded_file($tmpName, $targetFile)) {
            return $imageName;
        } else {
            $error = "Erreur lors de l'upload du fichier.";
        }
    }
    return null;
}


/**
 * Supprime un article.
 *
 * @param int $id Identifiant de l’article à supprimer.
 */
function deleteArticle($id)
{
    $articleModel = new \App\Models\Article();
    $articleModel->deleteArticle($id);
    header('Location: index.php?pageback=learn');
    exit;
}

/**
 * Point d’entrée AJAX pour rechercher et paginer les articles.
 *
 * - Lit GET : catégorie, search, p (page).
 * - Calcule offset et total pages.
 * - Renvoie JSON : articles, currentPage, totalPages.
 */
function searchLearn()
{
    header('Content-Type: application/json');

    $categorie = isset($_GET['categorie']) ? $_GET['categorie'] : 'Tous';
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $page = isset($_GET['p']) ? (int) $_GET['p'] : 1;
    if ($page < 1) {
        $page = 1;
    }

    // Nombre d'articles par page
    $articlesPerPage = 6;
    $offset = ($page - 1) * $articlesPerPage;

    // Récupération des articles filtrés
    $articleModel = new Article();
    $articles = $articleModel->getArticlesPublie($categorie, $search, $articlesPerPage, $offset);

    // Calcul du nombre total d'articles pour générer la pagination côté client
    $totalArticles = $articleModel->countArticles($categorie, $search);
    $totalPages = ceil($totalArticles / $articlesPerPage);

    // On renvoie le tout en JSON
    // 'articles' => liste d'articles
    // 'currentPage' => numéro de la page demandée
    // 'totalPages' => nombre total de pages
    echo json_encode([
        'articles' => $articles,
        'currentPage' => $page,
        'totalPages' => $totalPages
    ]);
    exit();
}
?>
