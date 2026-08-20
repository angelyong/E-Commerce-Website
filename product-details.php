<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: products.php');
    exit;
}

$stmt = $pdo->prepare('SELECT id, name, description, price, image, category, stock FROM products WHERE id = ?');
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: products.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> <?php echo htmlspecialchars($product['name']); ?> | E-COMMERCE WEBSITE BY EDYODA </title>
    <!-- favicon -->
    <link rel="icon" href="https://yt3.ggpht.com/a/AGF-l78km1YyNXmF0r3-0CycCA0HLA_i6zYn_8NZEg=s900-c-k-c0xffffffff-no-rj-mo" type="image/gif" sizes="16x16">
    <link rel="stylesheet" href="<?php echo asset('assets/css/main.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/product-details.css'); ?>">
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">

</head>

<body>
<!-- HEADER -->
<?php include 'includes/header.php'; ?>

    <div id="containerProduct">
        <div id="containerD">
            <div id="imageSection">
                <img id="imgDetails" src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>

            <div id="productDetails">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>

                <div id="details">
                    <h3>Rs <?php echo htmlspecialchars(number_format($product['price'], 2)); ?></h3>
                    <h3>Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    <p>
                        <?php if ($product['stock'] > 0): ?>
                            <?php echo (int) $product['stock']; ?> in stock
                        <?php else: ?>
                            Out of stock
                        <?php endif; ?>
                    </p>
                </div>

                <div id="button">
                    <?php if ($product['stock'] > 0): ?>
                        <form class="inlineForm" method="post" action="actions/add-to-cart.php">
                            <input type="hidden" name="product_id" value="<?php echo (int) $product['id']; ?>">
                            <button type="submit"> Add to Cart </button>
                        </form>
                    <?php else: ?>
                        <button type="button" disabled> Out of Stock </button>
                    <?php endif; ?>
                    <form class="inlineForm" method="post" action="actions/add-to-wishlist.php">
                        <input type="hidden" name="product_id" value="<?php echo (int) $product['id']; ?>">
                        <button type="submit"> Add to Wishlist </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- FOOTER -->
<?php include 'includes/footer.php'; ?>
</body>
</html>
