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
<div id="cookie-consent" class="cookie-popup">
  <p>We use cookies to improve your experience. By continuing to browse, you agree to our use of cookies.</p>
  <div class="cookie-buttons">
    <button id="acceptCookies" class="cookie-btn accept">Accept</button>
    <button id="declineCookies" class="cookie-btn decline">Decline</button>
  </div>
</div>

</div> <!-- Fermeture du wrapper principal du site -->
</body>
</html>
<script src="<?php echo RACINE_URL; ?>public/js/footer.js"></script>
