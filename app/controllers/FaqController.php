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

function createFaq()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $question = trim($_POST['question']);
        $reponse = trim($_POST['reponse']);

        $faqModel = new Faq();
        $validation = $faqModel->validateFaqData($question, $reponse);

        if (!$validation['success']) {
            $error = $validation['error'];
        } else {
            $faqModel->createFaq($question, $reponse);
            header('Location: index.php?pageback=faq&success=1');
            exit;
        }
    }

    require_once RACINE . 'app/views/backoffice/formFaq.php';
}


// Éditer une FAQ existante
function editFaq($id)
{
    if (!is_numeric($id)) {
        echo 'ID invalide.';
        exit;
    }

    $faqModel = new Faq();
    $faq = $faqModel->getById((int)$id);

    if (!$faq) {
        echo 'FAQ introuvable.';
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $question = trim($_POST['question']);
        $reponse = trim($_POST['reponse']);

        $validation = $faqModel->validateFaqData($question, $reponse);

        if (!$validation['success']) {
            $error = $validation['error'];
        } else {
            $faqModel->updateFaq($id, $question, $reponse);
            header('Location: index.php?pageback=faq&success=2');
            exit;
        }
    }

    require_once RACINE . 'app/views/backoffice/formFaq.php';
}


//  Supprimer une FAQ
function deleteFaq($id)
{
    if (!is_numeric($id)) {
        echo 'ID invalide.';
        exit;
    }

    $faqModel = new Faq();
    $faqModel->deleteFaq((int)$id);
    header('Location: index.php?pageback=faq&success=3');
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
