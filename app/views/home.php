<?php require_once RACINE . 'app/views/templates/header.php'; ?>

<div class="home-container">
    <!-- Section Hero avec phrase d'accroche, image de fond et bouton -->
    <section class="hero">
        <div class="hero-content">
            <h1>Votre <span class="orange">avenir</span>  crypto commence<span class="orange"> ici</span> </h1>
            <p>Rejoignez-nous pour explorer le monde passionnant de la crypto-monnaie</p>
            <?php if (isset($_SESSION['user'])): ?>
            <a href="index.php?page=dashboard" class="btn-get-started">Get Started</a>
                <?php else: ?>
                <a href="index.php?page=login" class="btn-get-started">Get Started</a>
                <?php endif; ?>
        </div>
    </section>
    <section class="home-features">
  <!-- Section 1 : Trade Virtual Funds -->
  <div class="feature trade">
    <div class="feature-bg" style="background-image: url('<?= RACINE_URL ?>public/image/trade-bg.jpg');"></div>
    <div class="feature-overlay"></div>
    <div class="feature-content">
      <h2><span class="orange">Trade</span> Virtual Funds</h2>
      <p>Simulez des transactions en temps réel avec des fonds virtuels pour tester vos stratégies sans risque.</p>
      <?php if (isset($_SESSION['user'])): ?>
            <a href="index.php?page=dashboard" class="btn-get-started">Let's go</a>
                <?php else: ?>
                <a href="index.php?page=login" class="btn-get-started">Let's go</a>
                <?php endif; ?>
    </div>
  </div>

  <!-- Section 2 : Learn about Crypto and Trading -->
  <?php require_once RACINE . 'app/views/templates/learnSection.php'; ?>

    <!-- Section regroupant les données de marché et l'image Fear & Greed -->
    <section class="market-fear">
        <div class="market-data">
            <?php require_once RACINE . 'app/views/templates/markettop10.php'; ?>
        </div>
        <section class="fear-leader">
                <?php require_once RACINE . 'app/views/templates/fearGreed.php'; ?>
                
                <div class="leader">
                    <?php require_once RACINE . 'app/views/templates/leaderboard.php'; ?>
                </div>
            </div>
        </section>
</section>
</div>

<?php require_once RACINE . 'app/views/templates/footer.php'; ?>
<script src="<?php echo RACINE_URL; ?>public/js/home.js"></script>
<script>async function loadFearIndex() {
    try {
        const res = await fetch("https://api.alternative.me/fng/");
        const data = await res.json();
        const value = parseInt(data.data[0].value);
        const classification = data.data[0].value_classification;

        // Affiche la valeur
        document.getElementById("index-value").textContent = value;

        // Met à jour l'aiguille (de -90° à +90°)
        const angle = (value / 100) * 180 - 90;
        document.getElementById("needle").style.transform = `rotate(${angle}deg)`;

        // Affiche la légende avec couleurs personnalisées
        const label = document.getElementById("index-label");
        label.textContent = classification;

        // Applique une couleur selon le niveau
        let color = "#f8f9fa";
        switch (classification.toLowerCase()) {
            case "extreme fear":
                color = "#e74c3c";
                break;
            case "fear":
                color = "#e67e22";
                break;
            case "neutral":
                color = "#f1c40f";
                break;
            case "greed":
                color = "#2ecc71";
                break;
            case "extreme greed":
                color = "#27ae60";
                break;
        }
        label.style.backgroundColor = color;
        label.style.color = "#fff";

    } catch (err) {
        console.error("Erreur lors du chargement de l'index :", err);
        document.getElementById("index-label").textContent = "Erreur";
    }
}

window.addEventListener("DOMContentLoaded", loadFearIndex);

</script>