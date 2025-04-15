</main>
<footer class="site-footer">
    <div class="footer-container">
        
        <ul class="footer-links">
            <?php if (isset($_SESSION['user'])): ?>
                <li><a href="index.php?page=logout">Logout</a></li>
            <?php endif; ?>
            <li><a href="index.php?page=mentions">Legal Notice</a></li>
            <li><a href="index.php?page=confidentialite">Privacy Policy</a></li>
        </ul>
        <p>© 2025 - CryptoInvest - All rights reserved.</p>
    </div>
</footer>

</div>
</body>
</html>
