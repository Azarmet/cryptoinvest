<?php
namespace App\Controllers;

use App\Models\Faq;

/**
 * Affiche la FAQ publique.
 *
 * - Récupère toutes les entrées via le modèle Faq.
 * - Charge la vue frontoffice/app/views/faq.php.
 */
function showFaq()
{
    $faqModel = new Faq();
    $faqs = $faqModel->getAll();
    require_once RACINE . 'app/views/faq.php';
}

/**
 * Affiche la FAQ dans le back-office.
 *
 * - Récupère toutes les entrées via le modèle Faq.
 * - Charge la vue backoffice/app/views/backoffice/faq.php.
 */
function showBackFaq()
{
    $faqModel = new Faq();
    $faqs = $faqModel->getAll();
    require_once RACINE . 'app/views/backoffice/faq.php';
}

/**
 * Crée une nouvelle entrée FAQ.
 *
 * - Valide la requête POST.
 * - Valide les données (question, réponse) via validateFaqData().
 * - Insère en base via createFaq().
 * - Redirige en back-office avec indicateur de succès.
 */
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


/**
 * Édite une entrée FAQ existante.
 *
 * @param $id Identifiant de la FAQ à modifier.
 *
 * - Vérifie que l'ID est numérique.
 * - Récupère la FAQ via getById().
 * - En POST, valide et met à jour via updateFaq().
 * - Redirige en back-office avec indicateur de succès.
 */
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


/**
 * Supprime une FAQ existante.
 *
 * @param $id Identifiant de la FAQ à supprimer.
 *
 * - Vérifie que l'ID est numérique.
 * - Supprime via deleteFaq().
 * - Redirige en back-office avec indicateur de succès.
 */
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


/**
 * Point d'entrée API pour la recherche de FAQ au format JSON.
 *
 * - Définit l'en-tête Content-Type.
 * - Lit le paramètre 'term' en GET.
 * - Si vide, renvoie toutes les FAQ, sinon celles correspondant au terme via search().
 * - Retourne le JSON et termine le script.
 */
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
