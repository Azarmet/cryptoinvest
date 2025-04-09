// Gestion recherche dynamique
document.getElementById('faq-search').addEventListener('input', function () {
    var searchTerm = this.value;
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "index.php?page=faq&action=search&term=" + encodeURIComponent(searchTerm) + "&t=" + new Date().getTime(), true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var faqs = JSON.parse(xhr.responseText);
            var container = document.getElementById('faq-results');
            container.innerHTML = "";

            if (faqs.length > 0) {
                faqs.forEach(function (faq) {
                    var item = document.createElement('div');
                    item.className = "faq-item";

                    var question = document.createElement('button');
                    question.className = "faq-question";
                    question.innerHTML = faq.question + '<span class="faq-toggle-icon">+</span>';

                    var answer = document.createElement('div');
                    answer.className = "faq-answer";
                    answer.innerHTML = "<p>" + faq.reponse.replace(/\n/g, "<br>") + "</p>";

                    question.addEventListener('click', function () {
                        toggleAccordion(item);
                    });
                    

                    item.appendChild(question);
                    item.appendChild(answer);
                    container.appendChild(item);
                });
            } else {
                container.innerHTML = "<p class='no-results'>Aucun résultat trouvé.</p>";
            }
        }
    };
    xhr.send();
});

function toggleAccordion(item) {
    const answer = item.querySelector('.faq-answer');

    if (item.classList.contains('active')) {
        // Fermer
        answer.style.height = answer.scrollHeight + 'px'; // set current height
        requestAnimationFrame(() => {
            answer.style.height = '0px';
        });
        item.classList.remove('active');
    } else {
        // Ouvrir
        answer.style.height = '0px'; // reset to 0 before transition
        item.classList.add('active');
        const fullHeight = answer.scrollHeight + 'px';
        requestAnimationFrame(() => {
            answer.style.height = fullHeight;
        });
    }

    // Nettoyage après transition
    answer.addEventListener('transitionend', function cleanup() {
        if (!item.classList.contains('active')) {
            answer.style.removeProperty('height');
        } else {
            answer.style.height = 'auto';
        }
        answer.removeEventListener('transitionend', cleanup);
    });
}

// Pour les éléments déjà présents au chargement
document.querySelectorAll('.faq-item').forEach(item => {
    const btn = item.querySelector('.faq-question');
    btn.addEventListener('click', () => toggleAccordion(item));
});
