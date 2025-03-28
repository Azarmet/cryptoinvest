<?php
namespace App\Controllers;

use App\Models\Article;

function showLearn()
{
    // Affichage initial de la page "learn".
    // Notez qu'ici, nous ne récupérons pas encore les articles : ce sera fait en AJAX.
    $categorie = isset($_GET['categorie']) ? $_GET['categorie'] : 'Tous';
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    require_once RACINE . 'app/views/learn.php';
}

function showBackLearn()
{
    $articleModel = new Article();
    $articles = $articleModel->getArticles();
    require_once RACINE . 'app/views/backoffice/learn.php';
}

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

function deleteArticle($id)
{
    $articleModel = new \App\Models\Article();
    $articleModel->deleteArticle($id);
    header('Location: index.php?pageback=learn');
    exit;
}

/**
 * Appelé en AJAX pour renvoyer la liste d'articles filtrés,
 * ainsi que la pagination (nombre total de pages, page courante).
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
    $articlesPerPage = 10;
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
