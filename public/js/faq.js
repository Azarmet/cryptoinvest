// ------------------ GESTION DE LA RECHERCHE DYNAMIQUE ------------------
// À chaque saisie dans le champ de recherche, on interroge l'API FAQ
document.getElementById('faq-search').addEventListener('input', function () {
    const searchTerm = this.value;
    const url = `index.php?page=faq&action=search&term=${encodeURIComponent(searchTerm)}&t=${new Date().getTime()}`;

    fetch(url)
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(faqs => {
        const container = document.getElementById('faq-results');
        container.innerHTML = "";

        if (faqs.length > 0) {
            // Pour chaque résultat, on crée les éléments question/réponse
            faqs.forEach(faq => {
                const item = document.createElement('div');
                item.className = "faq-item";

                // Bouton contenant la question et l'icône de toggle
                const question = document.createElement('button');
                question.className = "faq-question";
                question.innerHTML = faq.question + '<span class="faq-toggle-icon">+</span>';

                // Bloc contenant la réponse formatée en HTML
                const answer = document.createElement('div');
                answer.className = "faq-answer";
                answer.innerHTML = `<p>${faq.reponse.replace(/\n/g, "<br>")}</p>`;

                // Clic sur la question pour ouvrir/fermer l'accordéon
                question.addEventListener('click', () => {
                    toggleAccordion(item);
                });

                item.appendChild(question);
                item.appendChild(answer);
                container.appendChild(item);
            });
        } else {
            // Affichage d'un message si aucun résultat
            container.innerHTML = "<p class='no-results'>No result found.</p>";
        }
    })
    .catch(err => {
        console.error('Fetch error:', err);
    });
});

// ------------------ FONCTION BASCULE ACCORDÉON ------------------
// Ouvre ou ferme la réponse dans un effet de transition
function toggleAccordion(item) {
    const answer = item.querySelector('.faq-answer');

    if (item.classList.contains('active')) {
        // Fermer l'accordéon
        answer.style.height = answer.scrollHeight + 'px'; // hauteur initiale
        requestAnimationFrame(() => {
            answer.style.height = '0px';
        });
        item.classList.remove('active');
    } else {
        // Ouvrir l'accordéon
        answer.style.height = '0px'; // remise à zéro
        item.classList.add('active');
        const fullHeight = answer.scrollHeight + 'px';
        requestAnimationFrame(() => {
            answer.style.height = fullHeight;
        });
    }

    // Nettoyage après la fin de la transition
    answer.addEventListener('transitionend', function cleanup() {
        if (!item.classList.contains('active')) {
            answer.style.removeProperty('height');
        } else {
            answer.style.height = 'auto';
        }
        answer.removeEventListener('transitionend', cleanup);
    });
}

// ------------------ INITIALISATION DES ÉVÉNEMENTS EXISTANTS ------------------
// Ajoute le comportement toggle sur les FAQ déjà présentes au chargement
document.querySelectorAll('.faq-item').forEach(item => {
    const btn = item.querySelector('.faq-question');
    btn.addEventListener('click', () => toggleAccordion(item));
});
