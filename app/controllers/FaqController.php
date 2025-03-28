<?php
namespace App\Controllers;

use App\Models\Faq;

function showFaq()
{
    $faqModel = new Faq();
    $faqs = $faqModel->getAll();
    require_once RACINE . 'app/views/faq.php';
}

function showBackFaq()
{
    $faqModel = new Faq();
    $faqs = $faqModel->getAll();
    require_once RACINE . 'app/views/backoffice/faq.php';
}

// ✅ Créer une nouvelle FAQ
function createFaq()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $question = trim($_POST['question']);
        $reponse = trim($_POST['reponse']);

        if (!empty($question) && !empty($reponse)) {
            $faqModel = new Faq();
            $faqModel->createFaq($question, $reponse);
            header('Location: index.php?pageback=faq&success=1');  // ✅ Ajouté
            exit;
        } else {
            $error = 'Veuillez remplir tous les champs.';
        }
    }
    require_once RACINE . 'app/views/backoffice/formFaq.php';
}

// ✅ Éditer une FAQ existante
function editFaq($id)
{
    $faqModel = new Faq();
    $faq = $faqModel->getById($id);

    if (!$faq) {
        echo 'FAQ introuvable.';
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $question = trim($_POST['question']);
        $reponse = trim($_POST['reponse']);

        if (!empty($question) && !empty($reponse)) {
            $faqModel->updateFaq($id, $question, $reponse);
            header('Location: index.php?pageback=faq&success=2');  // ✅ Mise à jour
            exit;
        } else {
            $error = 'Veuillez remplir tous les champs.';
        }
    }

    require_once RACINE . 'app/views/backoffice/formFaq.php';
}

// ✅ Supprimer une FAQ
function deleteFaq($id)
{
    $faqModel = new Faq();
    $faqModel->deleteFaq($id);
    header('Location: index.php?pageback=faq&success=3');  // ✅ Suppression
    exit;
}

// API JSON
function searchFaq()
{
    header('Content-Type: application/json');
    $term = isset($_GET['term']) ? trim($_GET['term']) : '';
    $faqModel = new Faq();
    $faqs = !empty($term) ? $faqModel->search($term) : $faqModel->getAll();
    echo json_encode($faqs);
    exit();
}

?>
