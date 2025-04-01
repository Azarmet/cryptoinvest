
// Écouteur sur la barre de recherche pour déclencher la recherche dynamique
document.getElementById('faq-search').addEventListener('input', function(){
    var searchTerm = this.value;
    var xhr = new XMLHttpRequest();
    // On ajoute un paramètre timestamp pour éviter le cache
    xhr.open("GET", "index.php?page=faq&action=search&term=" + encodeURIComponent(searchTerm) + "&t=" + new Date().getTime(), true);
    xhr.onreadystatechange = function(){
         if(xhr.readyState == 4 && xhr.status == 200){
             var faqs = JSON.parse(xhr.responseText);
             var container = document.getElementById('faq-results');
             container.innerHTML = "";
             if(faqs.length > 0){
                faqs.forEach(function(faq){
                    var div = document.createElement('div');
                    div.className = "faq-item";
                    
                    var question = document.createElement('h3');
                    question.className ="faq-question";
                    question.textContent = faq.question;
                    
                    var reponse = document.createElement('p');
                    // Remplacer les sauts de ligne par <br>
                    reponse.className ="faq-answer";
                    reponse.innerHTML = faq.reponse.replace(/\n/g, "<br>");
                    
                    div.appendChild(question);
                    div.appendChild(reponse);
                    
                    container.appendChild(div);
                });
             } else {
                container.innerHTML = "<p>Aucun résultat trouvé.</p>";
             }
         }
    };
    xhr.send();
});
