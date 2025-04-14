</main>
<link rel="stylesheet" href="<?= RACINE_URL . 'public/css/templates/footer.css'?>">
<footer class="site-footer">
    <div class="footer-container">
        

        <ul class="footer-links">
            <?php if (isset($_SESSION['user'])): ?>
                <li><a href="index.php?page=logout">Déconnexion</a></li>
            <?php endif; ?>
            <li><a href="index.php?page=mentions">Mentions légales</a></li>
            <li><a href="index.php?page=confidentialite">Confidentialité</a></li>
        </ul>
        <p>© 2025 - CryptoInvest - Tous droits réservés.</p>
    </div>
</footer>

</div>
</body>
</html>
