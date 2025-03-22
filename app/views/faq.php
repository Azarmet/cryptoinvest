<?php require_once RACINE . "app/views/templates/header.php"; ?>

<h2>Foire aux Questions</h2>
<!-- Barre de recherche -->
<input type="text" id="faq-search" placeholder="Rechercher dans la FAQ...">

<!-- Conteneur pour afficher les FAQ -->
<div id="faq-results">
    <?php if (!empty($faqs)): ?>
        <?php foreach($faqs as $faq): ?>
            <div class="faq-item">
                <h3><?php echo htmlspecialchars($faq['question']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($faq['reponse'])); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun résultat trouvé.</p>
    <?php endif; ?>
</div>

<script>
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
                    question.textContent = faq.question;
                    
                    var reponse = document.createElement('p');
                    // Remplacer les sauts de ligne par <br>
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
</script>

<?php require_once RACINE . "app/views/templates/footer.php"; ?>
