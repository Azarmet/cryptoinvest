</main>

<footer class="back-footer">
    <p>
        © 2025 - CryptoInvest - All rights reserved.
        <?php if (isset($_SESSION['user'])): ?>
            <!-- Lien de déconnexion affiché uniquement si un utilisateur est connecté -->
            &nbsp;|&nbsp; <a href="index.php?pageback=logout">Logout</a>
        <?php endif; ?>
    </p>
</footer>

</body>
</html>
