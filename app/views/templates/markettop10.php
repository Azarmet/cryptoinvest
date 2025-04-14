<link rel="stylesheet" href="<?= RACINE_URL . 'public/css/templates/markettop10.css'?>">
<section class="market-top10">
    <h2>Top 10</h2>
    <table class="table-responsive">
        <thead>
            <tr>
                <th>Symbol</th>
                <th>Price (USD)</th>
                <th>Change (24h)</th>
            </tr>
        </thead>
        <tbody id="top10-market">
            <!-- Filled dynamically via JS -->
        </tbody>
    </table>
    <a href="index.php?page=market" class="btn-go-market">Go To Market</a>    
</section>
