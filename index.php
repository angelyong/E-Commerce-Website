<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

$featuredProducts = $pdo->query('SELECT id, name, price, image FROM products ORDER BY created_at DESC LIMIT 4')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> E-COMMERCE WEBSITE BY EDYODA | CREATED BY PRIYANKA SHARMA </title>
    <!-- favicon -->
    <link rel="icon" href="https://yt3.ggpht.com/a/AGF-l78km1YyNXmF0r3-0CycCA0HLA_i6zYn_8NZEg=s900-c-k-c0xffffffff-no-rj-mo" type="image/gif" sizes="16x16">
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('assets/css/main.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/products.css'); ?>">
</head>

<body>
    <!-- HEADER -->
    <?php include 'includes/header.php'; ?>

    <!-- SLIDER -->
    <section>
        <div id="containerSlider">
            <div class="slide active"><img src="assets/img/ui/img1.png" alt="promo banner 1"></div>
            <div class="slide"><img src="assets/img/ui/img2.png" alt="promo banner 2"></div>
            <div class="slide"><img src="assets/img/ui/img3.png" alt="promo banner 3"></div>
            <div class="slide"><img src="assets/img/ui/img4.png" alt="promo banner 4"></div>
        </div>
        <div id="sliderDots"></div>
    </section>

    <!-- FEATURED TEASER -->
    <div id="homeFeatured">
        <h2> New arrivals every week </h2>
        <p> Browse our full range of clothing and accessories. </p>
        <a href="products.php" id="shopNowBtn"> Shop Now </a>
    </div>

    <!-- FEATURED PRODUCTS -->
    <div id="mainContainer">
        <h1> featured products </h1>
        <div id="containerClothing">
            <?php foreach ($featuredProducts as $product): ?>
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
        </div>
    </div>

    <!-- FOOTER -->
    <?php include 'includes/footer.php'; ?>

    <script src="<?php echo asset('assets/js/main.js'); ?>"></script>
</body>

</html>
