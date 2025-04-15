<!-- SECTION 1 : Portfolio -->
<section id="portfolio-section" class="portfolio-section">
    <?php if ($_GET['page'] !== 'profilboard'): ?>
        <h3>My Portfolio</h3>
    <?php endif;?>
    <h3 id="current-portfolio-value">Current Value: Loading...</h3>

    <div class="portfolio-content">
      
        <div class="portfolio-left">
            <div class="interval-buttons">
                <button class="interval-btn" data-interval="jour">Day</button>
                <button class="interval-btn" data-interval="semaine">Week</button>
                <button class="interval-btn" data-interval="mois">Month</button>
                <button class="interval-btn" data-interval="annee">Year</button>
            </div>
            <div class="chart-container">
                <canvas id="portfolioChart"></canvas>
            </div>
        </div>
        <div class="portfolio-right">
            <div id="portfolio-stats" class="portfolio-stats">
                <div class="roi-pnl">
                    <div class="stat-item">
                        <p>Total ROI:</p>
                        <span id="roi-total">-</span>
                    </div>
                    <div class="stat-item">
                        <p>Total PnL:</p>
                        <span id="pnl-total">-</span>
                    </div>
                </div>
                
                <div class="stat-item transaction-count">
                    <p>Number of transactions:</p>
                    <span id="tx-count">-</span>
                </div>
            </div>
            <div class="portfolio-extra-stats">
                <ul>
                    <li>✅ Winning: <span id="stat-gagnantes"></span> | ❌ Losing: <span id="stat-perdantes"></span></li>
                    <li>📈 Longs: <span id="stat-long"></span> (✅ <span id="stat-long-win"></span>)</li>
                    <li>📉 Shorts: <span id="stat-short"></span> (✅ <span id="stat-short-win"></span>)</li>
                    <li>💸 Average PnL: <span id="stat-pnl-moyen"></span> USDT</li>
                    <li>⏱️ Average Position Time: <span id="stat-temps-moyen"></span> h</li>
                    <li>📊 Transactions / month: <span id="stat-tx-mois"></span></li>
                </ul>
            </div>
        </div>
    </div>
</section>
