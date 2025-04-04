<link rel="stylesheet" href="<?= RACINE_URL . 'public/css/portfolio.css'?>">
    <!-- SECTION 1 : Portefeuille -->
    <section id="portfolio-section" class="portfolio-section">
    
    <?php if ($_GET['page'] !== "profilboard"):?>
        <h3>Mon Portefeuille</h3>
        <?php endif;?>
    <div class="portfolio-content">
        <div class="portfolio-left">
            <div class="interval-buttons">
                <button class="interval-btn" data-interval="jour">Jour</button>
                <button class="interval-btn" data-interval="semaine">Semaine</button>
                <button class="interval-btn" data-interval="mois">Mois</button>
                <button class="interval-btn" data-interval="annee">Année</button>
            </div>
            <div class="chart-container">
                <canvas id="portfolioChart"></canvas>
            </div>
        </div>
        <div class="portfolio-right">
            <div id="portfolio-stats" class="portfolio-stats">
                <div class="stat-item">
                    <p>ROI Total :</p>
                    <span id="roi-total">-</span>
                </div>
                <div class="stat-item">
                    <p>PnL Total :</p>
                    <span id="pnl-total">-</span>
                </div>
                <div class="stat-item">
                    <p>Nombre de transactions :</p>
                    <span id="tx-count">-</span>
                </div>
            </div>
            <div class="portfolio-extra-stats">
                <ul>
                    <li>✅ Gagnantes : <span id="stat-gagnantes"></span> | ❌ Perdantes : <span id="stat-perdantes"></span></li>
                    <li>📈 Longs : <span id="stat-long"></span> (✅ <span id="stat-long-win"></span>)</li>
                    <li>📉 Shorts : <span id="stat-short"></span> (✅ <span id="stat-short-win"></span>)</li>
                    <li>💸 PnL moyen : <span id="stat-pnl-moyen"></span> USDT</li>
                    <li>⏱️ Temps moyen de position : <span id="stat-temps-moyen"></span> h</li>
                    <li>📊 Transactions / mois : <span id="stat-tx-mois"></span></li>
                </ul>
            </div>
        </div>
    </div>
</section>