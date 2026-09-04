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
<header class="site-header">
    <div class="header-inner">
        <a class="brand" href="index.php" aria-label="SHOPLANE home">SHOP<span>LANE</span></a>
        <nav class="primary-nav" aria-label="Primary navigation">
            <a href="products.php?category=clothing#clothing">Clothing</a>
            <a href="products.php?category=accessories#accessories">Accessories</a>
            <a href="about.php">About</a>
        </nav>
        <form class="site-search" method="get" action="products.php" role="search">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input type="search" name="q" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>" placeholder="Search for clothing and accessories" aria-label="Search products">
        </form>
        <div class="header-actions">
            <?php if (isLoggedIn()): ?>
                <a class="icon-button wishlist-link" href="wishlist.php" title="Wishlist" aria-label="Wishlist"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 20s-7-4.6-9.2-9C1.3 8.2 2.6 5 6 5c2 0 3.2 1.2 4 2.4C10.8 6.2 12 5 14 5c3.4 0 4.7 3.2 3.2 6-2.2 4.4-9.2 9-9.2 9z"></path></svg></a>
            <?php endif; ?>
            <a class="icon-button cart-link" href="cart.php" title="Cart" aria-label="Cart with <?php echo $cartCount; ?> items">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="9" cy="20" r="1.4"></circle><circle cx="18" cy="20" r="1.4"></circle><path d="M2 3h3l2.2 12.2a1.5 1.5 0 0 0 1.5 1.2h8.2a1.5 1.5 0 0 0 1.5-1.2L21 7H6"></path></svg>
                <span id="badge"><?php echo $cartCount; ?></span>
            </a>
            <?php if (isLoggedIn()): ?>
                <a class="icon-button" href="profile.php" title="<?php echo htmlspecialchars(currentUserName()); ?>" aria-label="Account">
            <?php else: ?>
                <a class="icon-button" href="login.php" title="Log in" aria-label="Log in">
            <?php endif; ?>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="8" r="3.4"></circle><path d="M5 20c0-3.6 3.1-6 7-6s7 2.4 7 6"></path></svg>
            </a>
        </div>
    </div>
</header>
