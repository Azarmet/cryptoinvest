<?php
namespace App\Controllers;

use App\Models\Faq;

function showFaq(){
    $faqModel = new Faq();
    $faqs = $faqModel->getAll();
    require_once RACINE . "app/views/faq.php";
}

function searchFaq(){
    header('Content-Type: application/json');
    $term = isset($_GET['term']) ? trim($_GET['term']) : '';
    $faqModel = new Faq();
    if(!empty($term)){
       $faqs = $faqModel->search($term);
    } else {
       $faqs = $faqModel->getAll();
    }
    echo json_encode($faqs);
    exit();
}
?>
