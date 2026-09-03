<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

$stmt = $pdo->query('SELECT id, name, price, image, category FROM products ORDER BY created_at DESC');
$products = $stmt->fetchAll();

$clothing = array_filter($products, function ($product) {
    return $product['category'] === 'clothing';
});
$accessories = array_filter($products, function ($product) {
    return $product['category'] === 'accessories';
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> PRODUCTS | E-COMMERCE WEBSITE BY EDYODA </title>
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
    <!-- favicon -->
    <link rel="icon" href="https://yt3.ggpht.com/a/AGF-l78km1YyNXmF0r3-0CycCA0HLA_i6zYn_8NZEg=s900-c-k-c0xffffffff-no-rj-mo" type="image/gif" sizes="16x16">

    <link rel="stylesheet" href= "assets/css/main.css">
    <link rel="stylesheet" href="<?php echo asset('assets/css/products.css'); ?>">

</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div id="mainContainer">

        <h1 id="clothing"> clothing for men and women </h1>
        <div id="containerClothing">
            <?php foreach ($clothing as $product): ?>
                <div id="box">
                    <a href="product-details.php?id=<?php echo (int) $product['id']; ?>">
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div id="details">
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            <h2>Rs <?php echo htmlspecialchars(number_format($product['price'], 2)); ?></h2>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
            <?php if (!$clothing): ?>
                <p> No clothing items yet. </p>
            <?php endif; ?>
        </div>

        <h1 id="accessories"> accessories for men and women </h1>
        <div id="containerAccessories">
            <?php foreach ($accessories as $product): ?>
                <div id="box">
                    <a href="product-details.php?id=<?php echo (int) $product['id']; ?>">
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div id="details">
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            <h2>Rs <?php echo htmlspecialchars(number_format($product['price'], 2)); ?></h2>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
            <?php if (!$accessories): ?>
                <p> No accessories yet. </p>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
