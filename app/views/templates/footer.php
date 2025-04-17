</main>

<footer class="site-footer">
    <div class="footer-container">
        <!-- Liens de bas de page -->
        <ul class="footer-links">
            <!-- Lien de déconnexion, uniquement si l'utilisateur est connecté -->
            <?php if (isset($_SESSION['user'])): ?>
                <li><a href="index.php?page=logout">Logout</a></li>
            <?php endif; ?>
            <!-- Lien vers la page des mentions légales -->
            <li><a href="index.php?page=mentions">Legal Notice</a></li>
            <!-- Lien vers la politique de confidentialité -->
            <li><a href="index.php?page=confidentialite">Privacy Policy</a></li>
        </ul>
        <!-- Copyright et informations légales -->
        <p>© 2025 - CryptoInvest - All rights reserved.</p>
    </div>
</footer>

</div> <!-- Fermeture du wrapper principal du site -->
</body>
</html>
