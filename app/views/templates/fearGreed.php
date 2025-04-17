<!-- Conteneur principal de l'indice Fear & Greed -->
<div class="fear">
    <!-- Titre de la section Fear & Greed -->
    <h2>Crypto Fear & Greed Index</h2>

    <!-- Conteneur global pour le cadran et la légende -->
    <div class="gauge-container">
        <!-- Zone du cadran graphique -->
        <div class="gauge">
            <!-- Aiguille du cadran, son orientation sera mise à jour par JavaScript -->
            <div class="needle" id="needle"></div>
        </div>

        <!-- Zone d'affichage de la valeur numérique et du libellé -->
        <div class="legend">
            <!-- Affiche la valeur actuelle de l'indice (0 à 100) -->
            <span id="index-value">--</span> / 100
            <!-- Affiche le libellé textuel (Fear, Greed, etc.), stylé via CSS -->
            <div id="index-label" class="label">Loading...</div>
        </div>
    </div>
</div>
