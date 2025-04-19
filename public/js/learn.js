// ------------------ VARIABLES GLOBALES DE FILTRAGE ------------------
var currentCategory = "tous";    // Catégorie sélectionnée ("tous" par défaut)
var currentSearch = "";          // Terme de recherche saisi
var currentPage = 1;             // Page actuelle (pagination)

// ------------------ INITIALISATION AU CHARGEMENT ------------------
window.addEventListener("DOMContentLoaded", function () {
  loadArticles(1);  // Charge la page 1 des articles dès le chargement
});

// ------------------ FONCTION AJAX POUR CHARGER LES ARTICLES ------------------
function loadArticles(page) {
  if (typeof page === "undefined") {
    page = 1;
  }
  currentPage = page;

  var xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    "index.php?page=learn&action=search" +
      "&categorie=" + encodeURIComponent(currentCategory) +
      "&search=" + encodeURIComponent(currentSearch) +
      "&p=" + currentPage +
      "&t=" + new Date().getTime(),  // Empêche la mise en cache
    true
  );

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      // Réponse JSON contenant { articles, currentPage, totalPages }
      var data = JSON.parse(xhr.responseText);
      var articles = data.articles || [];
      var container = document.getElementById("articles-container");
      container.innerHTML = "";

      // Mise à jour de la liste d’articles
      if (articles.length > 0) {
        articles.forEach(function (article) {
          var div = document.createElement("article");
          div.className = "article-item";
          div.style.padding = "10px";
          div.style.marginBottom = "10px";

          // Choix de l’image (article.image ou image par défaut)
          const imageSrc =
            article.image && article.image !== ""
              ? `public/uploads/article/${article.image}`
              : "public/image/default-article.jpg";

          // Construction du HTML de l’article
          var html = `
    <div class="article-image-container">
        <img src="${imageSrc}" alt="${article.titre}" class="article-image">
    </div>
    <h3>${article.titre}</h3>
    <p><em>${article.categorie} - ${article.date_publication}</em></p>
    <p>${article.contenu.substring(0, 200).replace(/(<([^>]+)>)/gi, "")}...</p>
    <a href='index.php?page=article&action=show&id=${article.id_article}'>Read more</a>
`;

          div.innerHTML = html;
          container.appendChild(div);
        });
      } else {
        // Message si aucun article trouvé
        container.innerHTML = "<p>No articles found.</p>";
      }

      // Mise à jour de la pagination
      buildPagination(data.currentPage, data.totalPages);
    }
  };
  xhr.send();
}

// ------------------ FONCTION DE CONSTRUCTION DE LA PAGINATION ------------------
function buildPagination(current, total) {
  const paginationDiv = document.getElementById("pagination");
  if (!paginationDiv) return;

  // Si une seule page, on n’affiche rien
  if (total <= 1) {
    paginationDiv.innerHTML = "";
    return;
  }

  let ul = '<ul style="list-style: none; display: flex; gap: 5px; flex-wrap: wrap; justify-content: center;">';

  // Lien "Previous" si on n’est pas sur la première page
  if (current > 1) {
    ul += `<li onclick="loadArticles(${current - 1});" class="page-link">Previous</li>`;
  }

  // Liens pour chaque numéro de page
  for (let i = 1; i <= total; i++) {
    if (i === current) {
      ul += `<li class="page-link active">${i}</li>`;
    } else {
      ul += `<li onclick="loadArticles(${i});" class="page-link">${i}</li>`;
    }
  }

  // Lien "Next" si on n’est pas sur la dernière page
  if (current < total) {
    ul += `<li onclick="loadArticles(${current + 1});" class="page-link">Next</li>`;
  }

  ul += "</ul>";
  paginationDiv.innerHTML = ul;
}

// ------------------ GESTION DES ONGLETS DE CATÉGORIE AVEC SCROLL ------------------
document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("category-tabs");
  const tabButtons = container.querySelectorAll(".tab-button");

  // Scroll automatique jusqu’à l’onglet actif au chargement
  const activeTab = container.querySelector(".tab-button.active");
  if (activeTab) {
    setTimeout(() => {
      activeTab.scrollIntoView({
        behavior: "smooth",
        inline: "center",
        block: "nearest"
      });
    }, 100);
  }

  // Clic sur un onglet
  tabButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      // Mise à jour de la catégorie courante
      currentCategory = this.getAttribute("data-category");

      // Actualisation de la classe active
      tabButtons.forEach(btn => btn.classList.remove("active"));
      this.classList.add("active");

      // Scroll vers l’onglet cliqué
      this.scrollIntoView({
        behavior: "smooth",
        inline: "center",
        block: "nearest"
      });

      // Réinitialisation de la recherche
      currentSearch = "";
      document.getElementById("learn-search").value = "";

      // Rechargement de la page 1
      loadArticles(1);
    });
  });
});

// ------------------ GESTION DE LA BARRE DE RECHERCHE ------------------
document.getElementById("learn-search").addEventListener("input", function () {
  // Mise à jour du terme de recherche et rechargement de la page 1
  currentSearch = this.value;
  loadArticles(1);
});
