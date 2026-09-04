<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

requireLogin();

$stmt = $pdo->prepare('SELECT ci.id, ci.quantity, p.name, p.price, p.image, p.stock FROM cart_items ci JOIN products p ON p.id = ci.product_id WHERE ci.user_id = ? ORDER BY ci.id DESC');
$stmt->execute([currentUserId()]);
$cartItems = $stmt->fetchAll();

$totalItems = 0;
$totalAmount = 0;
foreach ($cartItems as $item) {
    $totalItems += $item['quantity'];
    $totalAmount += $item['price'] * $item['quantity'];
}
$flashMessages = pullFlashMessages();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cart | SHOPLANE</title>
    <link rel="stylesheet" href="<?php echo asset('assets/css/main.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/cart.css'); ?>">

</head>
<body>
        <!-- HEADER -->
    <?php include 'includes/header.php'; ?>

        <!-- CART CONTAINER -->
    <main id="cartMainContainer">
        <h1> Checkout </h1>
        <h3 id="totalItem"> Total Items: <?php echo (int) $totalItems; ?> </h3>

        <?php foreach (($flashMessages['error'] ?? []) as $message): ?>
            <div class="cart-alert" role="alert"><?php echo htmlspecialchars($message); ?></div>
        <?php endforeach; ?>

        <div id="cartContainer">
            <?php if ($cartItems): ?>
                <div id="boxContainer">
                    <?php foreach ($cartItems as $item): ?>
                        <article class="cart-box" data-cart-row data-unit-price="<?php echo htmlspecialchars($item['price']); ?>">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <h3><?php echo htmlspecialchars($item['name']); ?> &times; <?php echo (int) $item['quantity']; ?></h3>
                            <h4>Unit price: Rs <?php echo htmlspecialchars(number_format($item['price'], 2)); ?> &middot; Subtotal: Rs <span data-line-total><?php echo htmlspecialchars(number_format($item['price'] * $item['quantity'], 2)); ?></span></h4>

                            <form class="cartItemForm" method="post" action="actions/update-cart.php">
                                <?php echo csrfField(); ?>
                                <input type="hidden" name="item_id" value="<?php echo (int) $item['id']; ?>">
                                <label class="sr-only" for="quantity-<?php echo (int) $item['id']; ?>">Quantity for <?php echo htmlspecialchars($item['name']); ?></label>
                                <input id="quantity-<?php echo (int) $item['id']; ?>" type="number" name="quantity" value="<?php echo (int) $item['quantity']; ?>" min="1" max="<?php echo (int) $item['stock']; ?>" data-cart-quantity>
                                <button type="submit"> Update </button>
                            </form>
                            <form class="cartItemForm" method="post" action="actions/remove-from-cart.php">
                                <?php echo csrfField(); ?>
                                <input type="hidden" name="item_id" value="<?php echo (int) $item['id']; ?>">
                                <button type="submit"> Remove </button>
                            </form>
                        </article>
                    <?php endforeach; ?>
                </div>

                <div id="totalContainer">
                    <div id="total">
                        <h2> Total Amount </h2>
                        <h4> Amount: Rs <span id="cartGrandTotal"><?php echo htmlspecialchars(number_format($totalAmount, 2)); ?></span> </h4>
                        <div id="button">
                            <form method="post" action="actions/place-order.php">
                                <?php echo csrfField(); ?>
                                <button type="submit"> Place Order </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div id="emptyState">
                    <p> Your cart is empty. </p>
                    <a href="products.php"> Continue shopping </a>
                </div>
            <?php endif; ?>
        </div>

    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="<?php echo asset('assets/js/cart.js'); ?>"></script>
</body>
</html>
