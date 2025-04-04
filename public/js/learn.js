// Variables globales de filtre
var currentCategory = "Tous";
var currentSearch = "";
// Page courante, on commencera à 1 par défaut
var currentPage = 1;

// Dès le chargement de la page, on appelle loadArticles(1) pour récupérer la page 1
window.addEventListener("DOMContentLoaded", function () {
  loadArticles(1);
});

// Fonction AJAX pour charger les articles filtrés
function loadArticles(page) {
  if (typeof page === "undefined") {
    page = 1;
  }
  currentPage = page;

  var xhr = new XMLHttpRequest();
  // On appelle l'action "searchLearn" pour récupérer la liste d'articles en JSON
  xhr.open(
    "GET",
    "index.php?page=learn&action=search" +
      "&categorie=" +
      encodeURIComponent(currentCategory) +
      "&search=" +
      encodeURIComponent(currentSearch) +
      "&p=" +
      currentPage +
      "&t=" +
      new Date().getTime(),
    true
  );

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      // On reçoit un objet JSON contenant : articles, currentPage, totalPages
      var data = JSON.parse(xhr.responseText);
      var articles = data.articles || [];
      var container = document.getElementById("articles-container");
      container.innerHTML = "";

      // Mise à jour de la liste d'articles
      if (articles.length > 0) {
        articles.forEach(function (article) {
          var div = document.createElement("div");
          div.className = "article-item";
          div.style.border = "1px solid #ccc";
          div.style.padding = "10px";
          div.style.marginBottom = "10px";

          const imageSrc =
            article.image && article.image !== ""
              ? `public/uploads/article/${article.image}`
              : "public/images/default-article.jpg"; // chemin image par défaut

          var html = `
    <div class="article-image-container">
        <img src="${imageSrc}" alt="${article.titre}" class="article-image">
    </div>
    <h3>${article.titre}</h3>
    <p><em>${article.categorie} - ${article.date_publication}</em></p>
    <p>${article.contenu.substring(0, 200).replace(/(<([^>]+)>)/gi, "")}...</p>
    <a href='index.php?page=article&action=show&id=${
      article.id_article
    }'>Lire la suite</a>
`;

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
    const paginationDiv = document.getElementById("pagination");
    if (!paginationDiv) return;
  
    if (total <= 1) {
      paginationDiv.innerHTML = "";
      return;
    }
  
    let ul = '<ul style="list-style: none; display: flex; gap: 5px; flex-wrap: wrap; justify-content: center;">';
  
    // Lien "Précédent"
    if (current > 1) {
      ul += `<li onclick="loadArticles(${current - 1});" class="page-link">Précédent</li>`;
    }
  
    // Liens pour chaque page
    for (let i = 1; i <= total; i++) {
      if (i === current) {
        ul += `<li class="page-link active">${i}</li>`;
      } else {
        ul += `<li onclick="loadArticles(${i});" class="page-link">${i}</li>`;
      }
    }
  
    // Lien "Suivant"
    if (current < total) {
      ul += `<li onclick="loadArticles(${current + 1});" class="page-link">Suivant</li>`;
    }
  
    ul += "</ul>";
    paginationDiv.innerHTML = ul;
  }
  

// Gestion des onglets de catégorie
document.querySelectorAll(".tab-button").forEach(function (button) {
  button.addEventListener("click", function () {
    // Récupère la catégorie depuis l'attribut data-category
    currentCategory = this.getAttribute("data-category");

    // Retire la classe active sur tous les onglets
    document.querySelectorAll(".tab-button").forEach(function (btn) {
      btn.classList.remove("active");
    });
    // Ajoute la classe active sur l'onglet cliqué
    this.classList.add("active");

    // Réinitialise la recherche
    currentSearch = "";
    document.getElementById("learn-search").value = "";

    // Charge la page 1 pour la catégorie sélectionnée
    loadArticles(1);
  });
});

// Gestion de la recherche (bouton Rechercher)
document.getElementById("learn-search").addEventListener("input", function () {
  currentSearch = this.value;
  loadArticles(1);
});
