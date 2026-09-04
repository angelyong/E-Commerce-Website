<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

$allProducts = $pdo->query('SELECT id, name, price, image, category, stock, created_at FROM products ORDER BY created_at DESC')->fetchAll();
$clothing = array_values(array_filter($allProducts, fn($product) => $product['category'] === 'clothing'));
$accessories = array_values(array_filter($allProducts, fn($product) => $product['category'] === 'accessories'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHOPLANE | Clothing and accessories</title>
    <meta name="description" content="Shop clothing and accessories for men and women at SHOPLANE.">
    <link rel="stylesheet" href="<?php echo asset('assets/css/main.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/products.css'); ?>">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <main>
        <section class="hero-section" aria-label="Featured promotions">
            <div class="hero-slider" id="containerSlider">
                <article class="hero-slide active">
                    <div class="hero-copy"><span>Men's wear</span><h1>Flat 50% off the season edit</h1><p>Shirts, chinos and layers built for everyday. Limited-time pricing on the full men's range.</p><a href="products.php#clothing" class="primary-button">Shop now <span>&rarr;</span></a></div>
                    <div class="hero-image"><img src="assets/img/ui/img1.png" alt="Men's fashion promotion"></div>
                </article>
                <article class="hero-slide">
                    <div class="hero-copy"><span>Women's wear</span><h1>Fresh styles, every single week</h1><p>Dresses, denim and staples restocked weekly. Find your next favourite piece.</p><a href="products.php#clothing" class="primary-button">Shop now <span>&rarr;</span></a></div>
                    <div class="hero-image"><img src="assets/img/ui/img2.png" alt="Women's fashion promotion"></div>
                </article>
                <article class="hero-slide">
                    <div class="hero-copy"><span>Accessories</span><h1>The details that finish the fit</h1><p>Bags, watches and eyewear to complete any look. New drops just landed.</p><a href="products.php#accessories" class="primary-button">Shop now <span>&rarr;</span></a></div>
                    <div class="hero-image"><img src="assets/img/ui/img3.png" alt="Accessories promotion"></div>
                </article>
                <button class="slider-arrow previous" type="button" aria-label="Previous slide">&#8249;</button>
                <button class="slider-arrow next" type="button" aria-label="Next slide">&#8250;</button>
                <div id="sliderDots" class="slider-dots"></div>
            </div>
        </section>

        <section class="trust-strip" aria-label="Shopping benefits">
            <div><span>&check;</span><p><strong>Free shipping</strong><small>On orders over Rs 75</small></p></div>
            <div><span>&#8634;</span><p><strong>30-day returns</strong><small>No questions asked</small></p></div>
            <div><span>&#9670;</span><p><strong>Secure checkout</strong><small>Protected ordering</small></p></div>
            <div><span>&starf;</span><p><strong>New weekly</strong><small>Fresh arrivals</small></p></div>
        </section>

        <section class="product-section" id="clothing">
            <div class="section-heading"><div><span>New arrivals every week</span><h2>Clothing for men &amp; women</h2></div><a href="products.php#clothing">View all &rarr;</a></div>
            <div class="product-grid"><?php foreach (array_slice($clothing, 0, 4) as $product): include 'includes/product-card.php'; endforeach; ?></div>
        </section>

        <section class="product-section" id="accessories">
            <div class="section-heading"><div><span>Finish the look</span><h2>Accessories for everyone</h2></div><a href="products.php#accessories">View all &rarr;</a></div>
            <div class="product-grid"><?php foreach (array_slice($accessories, 0, 4) as $product): include 'includes/product-card.php'; endforeach; ?></div>
        </section>

        <section class="cta-band">
            <div><h2>Save your favourites and checkout faster</h2><p>Create an account for wishlists, a persistent cart, and order access.</p></div>
            <?php if (isLoggedIn()): ?><a class="primary-button" href="products.php">Shop arrivals</a><?php else: ?><a class="primary-button" href="register.php">Create account</a><?php endif; ?>
        </section>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="<?php echo asset('assets/js/main.js'); ?>"></script>
</body>
</html>
