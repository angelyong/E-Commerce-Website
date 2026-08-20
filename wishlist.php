<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

requireLogin();

$stmt = $pdo->prepare('SELECT wi.id, p.id AS product_id, p.name, p.price, p.image FROM wishlist_items wi JOIN products p ON p.id = wi.product_id WHERE wi.user_id = ? ORDER BY wi.id DESC');
$stmt->execute([currentUserId()]);
$wishlistItems = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Wishlist | E-COMMERCE WEBSITE BY EDYODA </title>
    <link rel="stylesheet" href="<?php echo asset('assets/css/main.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/cart.css'); ?>">
    <!-- favicon -->
    <link rel="icon" href="https://yt3.ggpht.com/a/AGF-l78km1YyNXmF0r3-0CycCA0HLA_i6zYn_8NZEg=s900-c-k-c0xffffffff-no-rj-mo" type="image/gif" sizes="16x16">
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div id="cartMainContainer">
        <h1> Wishlist </h1>

        <div id="cartContainer">
            <?php if ($wishlistItems): ?>
                <div id="boxContainer">
                    <?php foreach ($wishlistItems as $item): ?>
                        <div id="box">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                            <h4>Amount: Rs <?php echo htmlspecialchars(number_format($item['price'], 2)); ?></h4>

                            <form class="cartItemForm" method="post" action="actions/add-to-cart.php">
                                <input type="hidden" name="product_id" value="<?php echo (int) $item['product_id']; ?>">
                                <button type="submit"> Add to Cart </button>
                            </form>
                            <form class="cartItemForm" method="post" action="actions/remove-from-wishlist.php">
                                <input type="hidden" name="item_id" value="<?php echo (int) $item['id']; ?>">
                                <button type="submit"> Remove </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div id="emptyState">
                    <p> Your wishlist is empty. </p>
                    <a href="products.php"> Continue shopping </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
