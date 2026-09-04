<footer class="site-footer">
    <div class="footer-grid">
        <div class="footer-about">
            <a class="brand" href="index.php">SHOP<span>LANE</span></a>
            <p>Clothing and accessories for men and women. New arrivals every week, delivered to your door.</p>
        </div>
        <div class="footer-column"><h3>Shop</h3><a href="products.php?category=clothing#clothing">Clothing</a><a href="products.php?category=accessories#accessories">Accessories</a><a href="products.php">New arrivals</a><a href="products.php">All products</a></div>
        <div class="footer-column"><h3>Help</h3><a href="help.php#shipping">Shipping</a><a href="help.php#returns">Returns</a><a href="contact.php">Contact</a><?php if (isLoggedIn()): ?><a href="profile.php">My account</a><?php else: ?><a href="login.php">Log in</a><?php endif; ?></div>
        <div class="footer-column"><h3>Company</h3><a href="about.php#story">About us</a><a href="about.php#stores">Stores</a><a href="about.php#partnerships">Partnerships</a><a href="about.php#sustainability">Sustainability</a></div>
    </div>
    <div class="footer-bottom"><span>&copy; <?php echo date('Y'); ?> SHOPLANE. All rights reserved.</span><div><a href="help.php#privacy">Privacy</a><a href="help.php#terms">Terms</a><a href="help.php#cookies">Cookies</a></div></div>
</footer>
