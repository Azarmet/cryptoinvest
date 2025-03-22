// Variables globales de filtre
var currentCategory = "Tous";
var currentSearch = "";
// Page courante, on commencera à 1 par défaut
var currentPage = 1;

// Dès le chargement de la page, on appelle loadArticles(1) pour récupérer la page 1
window.addEventListener('DOMContentLoaded', function(){
    loadArticles(1);
});

// Fonction AJAX pour charger les articles filtrés
function loadArticles(page){
    if(typeof page === "undefined"){
       page = 1;
    }
    currentPage = page;

    var xhr = new XMLHttpRequest();
    // On appelle l'action "searchLearn" pour récupérer la liste d'articles en JSON
    xhr.open("GET", "index.php?page=learn&action=search"
        + "&categorie=" + encodeURIComponent(currentCategory)
        + "&search=" + encodeURIComponent(currentSearch)
        + "&p=" + currentPage
        + "&t=" + new Date().getTime()
    , true);

    xhr.onreadystatechange = function(){
         if(xhr.readyState === 4 && xhr.status === 200){
             // On reçoit un objet JSON contenant : articles, currentPage, totalPages
             var data = JSON.parse(xhr.responseText);
             var articles = data.articles || [];
             var container = document.getElementById('articles-container');
             container.innerHTML = "";

             // Mise à jour de la liste d'articles
             if(articles.length > 0){
                 articles.forEach(function(article){
                     var div = document.createElement('div');
                     div.className = "article-item";
                     div.style.border = "1px solid #ccc";
                     div.style.padding = "10px";
                     div.style.marginBottom = "10px";
                     
                     var html = "<h3>" + article.titre + "</h3>"
                              + "<p><em>" + article.categorie + " - " + article.date_publication + "</em></p>"
                              + "<p>" + article.contenu.substring(0,200).replace(/(<([^>]+)>)/ig, "") + "...</p>"
                              + "<a href='index.php?page=article&action=show&id=" + article.id_article + "'>Lire la suite</a>";
                     div.innerHTML = html;
                     container.appendChild(div);
                 });
             } else {
                 container.innerHTML = "<p>Aucun article trouvé.</p>";
             }

             // Mise à jour de la pagination
             buildPagination(data.currentPage, data.totalPages);
         }
    };
    xhr.send();
}

/**
 * Construit la pagination en JavaScript, dans le div#pagination.
 */
function buildPagination(current, total) {
    var paginationDiv = document.getElementById('pagination');
    if(!paginationDiv) {
        return;
    }
    // S'il n'y a qu'une seule page, on vide la pagination
    if(total <= 1){
        paginationDiv.innerHTML = "";
        return;
    }

    // On va construire une liste <ul> d'éléments <li>
    var ul = '<ul style="list-style: none; display: flex; gap: 5px;">';

    // Lien "Précédent"
    if(current > 1) {
        ul += '<li><a href="#" onclick="loadArticles(' + (current - 1) + '); return false;">Précédent</a></li>';
    }

    // Liens pour chaque page
    for(var i = 1; i <= total; i++){
        if(i === current){
            ul += '<li><strong>' + i + '</strong></li>';
        } else {
            ul += '<li><a href="#" onclick="loadArticles(' + i + '); return false;">' + i + '</a></li>';
        }
    }

    // Lien "Suivant"
    if(current < total){
        ul += '<li><a href="#" onclick="loadArticles(' + (current + 1) + '); return false;">Suivant</a></li>';
    }
    ul += '</ul>';

    paginationDiv.innerHTML = ul;
}

// Gestion des onglets de catégorie
document.querySelectorAll('.tab-button').forEach(function(button){
    button.addEventListener('click', function(){
         // Récupère la catégorie depuis l'attribut data-category
         currentCategory = this.getAttribute('data-category');

         // Retire la classe active sur tous les onglets
         document.querySelectorAll('.tab-button').forEach(function(btn){
             btn.classList.remove('active');
         });
         // Ajoute la classe active sur l'onglet cliqué
         this.classList.add('active');

         // Réinitialise la recherche
         currentSearch = "";
         document.getElementById('learn-search').value = "";

         // Charge la page 1 pour la catégorie sélectionnée
         loadArticles(1);
    });
});

// Gestion de la recherche (bouton Rechercher)
document.getElementById('learn-search').addEventListener('input', function(){
    currentSearch = this.value;
    loadArticles(1);
});