<?php
$isNew = isset($product['created_at']) && strtotime($product['created_at']) >= strtotime('-30 days');
$returnPath = $_SERVER['REQUEST_URI'] ?? '/products.php';
?>
<article class="product-card" data-product-card data-name="<?php echo htmlspecialchars(strtolower($product['name'])); ?>" data-search="<?php echo htmlspecialchars(strtolower($product['name'] . ' ' . ($product['description'] ?? '') . ' ' . $product['category'])); ?>" data-category="<?php echo htmlspecialchars($product['category']); ?>" data-price="<?php echo htmlspecialchars($product['price']); ?>" data-stock="<?php echo (int) ($product['stock'] ?? 0); ?>">
    <div class="product-media">
        <?php if ($isNew): ?><span class="product-badge">New</span><?php endif; ?>
        <?php if (isLoggedIn()): ?>
            <form class="wishlist-form" method="post" action="actions/add-to-wishlist.php">
                <?php echo csrfField(); ?>
                <input type="hidden" name="product_id" value="<?php echo (int) $product['id']; ?>">
                <button class="heart-button" type="submit" title="Add to wishlist" aria-label="Add <?php echo htmlspecialchars($product['name']); ?> to wishlist"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 20s-7-4.6-9.2-9C1.3 8.2 2.6 5 6 5c2 0 3.2 1.2 4 2.4C10.8 6.2 12 5 14 5c3.4 0 4.7 3.2 3.2 6-2.2 4.4-9.2 9-9.2 9z"></path></svg></button>
            </form>
        <?php else: ?>
            <a class="heart-button" href="login.php?redirect=<?php echo urlencode($returnPath); ?>" title="Log in to use wishlist" aria-label="Log in to use wishlist"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 20s-7-4.6-9.2-9C1.3 8.2 2.6 5 6 5c2 0 3.2 1.2 4 2.4C10.8 6.2 12 5 14 5c3.4 0 4.7 3.2 3.2 6-2.2 4.4-9.2 9-9.2 9z"></path></svg></a>
        <?php endif; ?>
        <a href="product-details.php?id=<?php echo (int) $product['id']; ?>"><img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>"></a>
    </div>
    <div class="product-info">
        <a class="product-name" href="product-details.php?id=<?php echo (int) $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
        <div class="product-buy-row">
            <strong>Rs <?php echo htmlspecialchars(number_format($product['price'], 2)); ?></strong>
            <?php if (isLoggedIn()): ?>
                <form method="post" action="actions/add-to-cart.php">
                    <?php echo csrfField(); ?>
                    <input type="hidden" name="product_id" value="<?php echo (int) $product['id']; ?>">
                    <button class="add-button" type="submit" <?php echo ((int) ($product['stock'] ?? 1) <= 0) ? 'disabled' : ''; ?>><span>+</span> <?php echo ((int) ($product['stock'] ?? 1) <= 0) ? 'Sold out' : 'Add'; ?></button>
                </form>
            <?php else: ?>
                <a class="add-button" href="login.php?redirect=<?php echo urlencode($returnPath); ?>"><span>+</span> Add</a>
            <?php endif; ?>
        </div>
    </div>
</article>
