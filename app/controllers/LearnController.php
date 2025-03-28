<?php
namespace App\Controllers;

use App\Models\Article;

function showLearn() {
    // Affichage initial de la page "learn".
    // Notez qu'ici, nous ne récupérons pas encore les articles : ce sera fait en AJAX.
    $categorie = isset($_GET['categorie']) ? $_GET['categorie'] : 'Tous';
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    require_once RACINE . "app/views/learn.php";
}

function showBackLearn(){
    $articleModel = new Article();
    $articles = $articleModel->getArticles();
    require_once RACINE . "app/views/backoffice/learn.php";
}

function showArticleDetail($id) {
    $articleModel = new \App\Models\Article();
    $article = $articleModel->getById($id);

    if ($article) {
        require_once RACINE . "app/views/detailArticle.php";
    } else {
        echo "Article introuvable.";
    }
}

function createArticle() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $imageName = changeImage();

        $articleModel = new \App\Models\Article();

        $data = [
            'titre' => $_POST['titre'],
            'contenu' => $_POST['contenu'],
            'auteur' => $_SESSION['user']['id_utilisateur'],
            'categorie' => $_POST['categorie'],
            'statut' => $_POST['statut'],
            'image' => $imageName
        ];

        $articleModel->createArticle($data);
        header("Location: index.php?pageback=learn");
        exit;
    }

    require_once RACINE . "app/views/backoffice/formArticle.php";
}


function editArticle($id) {
    $articleModel = new \App\Models\Article();
    $article = $articleModel->getById($id);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $imageName = changeImage();
        if (!$imageName) {
            $imageName = $article['image']; // garde l'ancienne image
        }

        $data = [
            'titre' => $_POST['titre'],
            'contenu' => $_POST['contenu'],
            'auteur' => $_SESSION['user']['id_utilisateur'],
            'categorie' => $_POST['categorie'],
            'statut' => $_POST['statut'],
            'image' => $imageName
        ];

        $articleModel->updateArticle($id, $data);
        header("Location: index.php?pageback=learn");
        exit;
    }

    require_once RACINE . "app/views/backoffice/formArticle.php";
}


function changeImage() {
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === 0) {
        $targetDir = "public/uploads/article/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = uniqid() . "." . $extension;
        $targetFile = $targetDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            return $imageName;
        }
    }
    return null;
}



function deleteArticle($id) {
    $articleModel = new \App\Models\Article();
    $articleModel->deleteArticle($id);
    header("Location: index.php?pageback=learn");
    exit;
}


/**
 * Appelé en AJAX pour renvoyer la liste d'articles filtrés,
 * ainsi que la pagination (nombre total de pages, page courante).
 */
function searchLearn(){
    header('Content-Type: application/json');

    $categorie = isset($_GET['categorie']) ? $_GET['categorie'] : 'Tous';
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
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
