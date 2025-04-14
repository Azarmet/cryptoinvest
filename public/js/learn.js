// Global filter variables
var currentCategory = "tous";
var currentSearch = "";
// Current page, default starts at 1
var currentPage = 1;

// As soon as the page loads, call loadArticles(1) to get page 1
window.addEventListener("DOMContentLoaded", function () {
  loadArticles(1);
});

// AJAX function to load filtered articles
function loadArticles(page) {
  if (typeof page === "undefined") {
    page = 1;
  }
  currentPage = page;

  var xhr = new XMLHttpRequest();
  // Call the "searchLearn" action to retrieve the list of articles in JSON
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
      // We receive a JSON object containing: articles, currentPage, totalPages
      var data = JSON.parse(xhr.responseText);
      var articles = data.articles || [];
      var container = document.getElementById("articles-container");
      container.innerHTML = "";

      // Update the list of articles
      if (articles.length > 0) {
        articles.forEach(function (article) {
          var div = document.createElement("div");
          div.className = "article-item";
          div.style.padding = "10px";
          div.style.marginBottom = "10px";

          const imageSrc =
            article.image && article.image !== ""
              ? `public/uploads/article/${article.image}`
              : "public/image/default-article.jpg"; // default image path

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
        container.innerHTML = "<p>No articles found.</p>";
      }

      // Update pagination
      buildPagination(data.currentPage, data.totalPages);
    }
  };
  xhr.send();
}

/**
 * Builds the pagination in JavaScript, inside the div#pagination.
 */
function buildPagination(current, total) {
    const paginationDiv = document.getElementById("pagination");
    if (!paginationDiv) return;
  
    if (total <= 1) {
      paginationDiv.innerHTML = "";
      return;
    }
  
    let ul = '<ul style="list-style: none; display: flex; gap: 5px; flex-wrap: wrap; justify-content: center;">';
  
    // "Previous" link
    if (current > 1) {
      ul += `<li onclick="loadArticles(${current - 1});" class="page-link">Previous</li>`;
    }
  
    // Links for each page
    for (let i = 1; i <= total; i++) {
      if (i === current) {
        ul += `<li class="page-link active">${i}</li>`;
      } else {
        ul += `<li onclick="loadArticles(${i});" class="page-link">${i}</li>`;
      }
    }
  
    // "Next" link
    if (current < total) {
      ul += `<li onclick="loadArticles(${current + 1});" class="page-link">Next</li>`;
    }
  
    ul += "</ul>";
    paginationDiv.innerHTML = ul;
}
  

// Category tab management with scroll
document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("category-tabs");
  const tabButtons = container.querySelectorAll(".tab-button");

  // Automatic scroll to the active tab on load
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

  // Handle click
  tabButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      // Update category
      currentCategory = this.getAttribute("data-category");

      // Remove the active class
      tabButtons.forEach(function (btn) {
        btn.classList.remove("active");
      });

      // Add the active class to the clicked button
      this.classList.add("active");

      // Scroll to the button
      this.scrollIntoView({
        behavior: "smooth",
        inline: "center",
        block: "nearest"
      });

      // Reset search
      currentSearch = "";
      document.getElementById("learn-search").value = "";

      // Reload articles
      loadArticles(1);
    });
  });
});

// Search management (for the search input)
document.getElementById("learn-search").addEventListener("input", function () {
  currentSearch = this.value;
  loadArticles(1);
});
