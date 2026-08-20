<?php
require_once __DIR__ . '/auth.php';

$cartCount = 0;
if (isLoggedIn()) {
    require_once __DIR__ . '/db.php';
    $stmt = $pdo->prepare('SELECT COALESCE(SUM(quantity), 0) FROM cart_items WHERE user_id = ?');
    $stmt->execute([currentUserId()]);
    $cartCount = (int) $stmt->fetchColumn();
}
?>
<header>
    <section>
        <!-- MAIN CONTAINER -->
        <div id="container">
            <!-- SHOP NAME -->
            <div id="shopName"><a href="index.php"> <b>SHOP</b>LANE </a></div>
                <!-- COLLCETIONS ON WEBSITE -->
                <div id="collection">
                    <div id="clothing"><a href="products.php#clothing"> CLOTHING </a></div>
                    <div id="accessories"><a href="products.php#accessories"> ACCESSORIES </a></div>
                </div>
                <!-- SEARCH SECTION -->
                <div id="search">
                    <svg class="search" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" id="input" name="searchBox" placeholder="Search for Clothing and Accessories">
                </div>
                <!-- USER SECTION (CART AND USER ICON) -->
                <div id="user">
                    <?php if (isLoggedIn()): ?>
                        <a href="wishlist.php" title="Wishlist"> <svg class="userIcon" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 21s-6.7-4.35-9.3-8.1C1 10.1 1.6 6.6 4.4 5.1c2.2-1.2 4.6-.4 6 1.3l1.6 1.9 1.6-1.9c1.4-1.7 3.8-2.5 6-1.3 2.8 1.5 3.4 5 1.7 7.8C18.7 16.65 12 21 12 21z"></path></svg> </a>
                    <?php endif; ?>
                    <a href="cart.php" title="Cart"> <span class="addedToCart"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg><div id="badge"> <?php echo $cartCount; ?> </div></span></a>
                    <?php if (isLoggedIn()): ?>
                        <a href="profile.php" title="<?php echo htmlspecialchars(currentUserName()); ?>"> <svg class="userIcon" width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="9" r="3.2"></circle><path d="M5.5 19.2a7.5 7.5 0 0 1 13 0"></path></svg> </a>
                    <?php else: ?>
                        <a href="login.php" title="Log in"> <svg class="userIcon" width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="9" r="3.2"></circle><path d="M5.5 19.2a7.5 7.5 0 0 1 13 0"></path></svg> </a>
                    <?php endif; ?>
                </div>
        </div>

    </section>
</header>
