<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

requireLogin();

$stmt = $pdo->prepare('SELECT ci.id, ci.quantity, p.name, p.price, p.image FROM cart_items ci JOIN products p ON p.id = ci.product_id WHERE ci.user_id = ? ORDER BY ci.id DESC');
$stmt->execute([currentUserId()]);
$cartItems = $stmt->fetchAll();

$totalItems = 0;
$totalAmount = 0;
foreach ($cartItems as $item) {
    $totalItems += $item['quantity'];
    $totalAmount += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Cart | E-COMMERCE WEBSITE BY EDYODA </title>
    <link rel="stylesheet" href="<?php echo asset('assets/css/main.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/cart.css'); ?>">
    <!-- favicon -->
    <link rel="icon" href="https://yt3.ggpht.com/a/AGF-l78km1YyNXmF0r3-0CycCA0HLA_i6zYn_8NZEg=s900-c-k-c0xffffffff-no-rj-mo" type="image/gif" sizes="16x16">
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">

</head>
<body>
        <!-- HEADER -->
    <?php include 'includes/header.php'; ?>

        <!-- CART CONTAINER -->
    <div id="cartMainContainer">
        <h1> Checkout </h1>
        <h3 id="totalItem"> Total Items: <?php echo (int) $totalItems; ?> </h3>

        <div id="cartContainer">
            <?php if ($cartItems): ?>
                <div id="boxContainer">
                    <?php foreach ($cartItems as $item): ?>
                        <div id="box">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <h3><?php echo htmlspecialchars($item['name']); ?> &times; <?php echo (int) $item['quantity']; ?></h3>
                            <h4>Amount: Rs <?php echo htmlspecialchars(number_format($item['price'], 2)); ?></h4>

                            <form class="cartItemForm" method="post" action="actions/update-cart.php">
                                <input type="hidden" name="item_id" value="<?php echo (int) $item['id']; ?>">
                                <input type="number" name="quantity" value="<?php echo (int) $item['quantity']; ?>" min="1">
                                <button type="submit"> Update </button>
                            </form>
                            <form class="cartItemForm" method="post" action="actions/remove-from-cart.php">
                                <input type="hidden" name="item_id" value="<?php echo (int) $item['id']; ?>">
                                <button type="submit"> Remove </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div id="totalContainer">
                    <div id="total">
                        <h2> Total Amount </h2>
                        <h4> Amount: Rs <?php echo htmlspecialchars(number_format($totalAmount, 2)); ?> </h4>
                        <div id="button">
                            <form method="post" action="actions/place-order.php">
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

    </div>

</body>
    <!-- FOOTER -->
    <?php include 'includes/footer.php'; ?>
</html>
