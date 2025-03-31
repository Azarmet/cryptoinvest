</main>
    <footer class="site-footer">
        <div class="footer-container">
            <p>© 2025 - CryptoInvest - Tous droits réservés.
                <?php if (isset($_SESSION['user'])): ?>
                    - <a href="index.php?page=logout">Déconnexion</a>
                <?php endif; ?>
            </p>
        </div>
    </footer>
</body>
</html>
