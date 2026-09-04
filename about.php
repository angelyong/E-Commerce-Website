<?php
require_once __DIR__ . '/includes/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About us | SHOPLANE</title>
    <meta name="description" content="Learn about SHOPLANE, our stores, partnerships and approach to responsible fashion.">
    <link rel="stylesheet" href="<?php echo asset('assets/css/main.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/info.css'); ?>">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <main class="info-page">
        <header class="info-hero"><span>Our company</span><h1>Style for everyday life</h1><p>SHOPLANE brings clothing and accessories together in one straightforward, easy-to-use online store.</p></header>
        <div class="info-grid">
            <section id="story"><span>01</span><div><h2>Our story</h2><p>We focus on useful wardrobe pieces, clear product information and a smooth shopping experience from discovery to checkout.</p></div></section>
            <section id="stores"><span>02</span><div><h2>Stores</h2><p>Our online store is always open. For questions about products or ordering, contact the support team Monday to Friday, 9:00 AM–5:00 PM.</p><a href="contact.php">Contact the store &rarr;</a></div></section>
            <section id="partnerships"><span>03</span><div><h2>Partnerships</h2><p>We welcome responsible retail and supplier partnerships that improve product choice and customer service.</p><a href="contact.php">Discuss a partnership &rarr;</a></div></section>
            <section id="sustainability"><span>04</span><div><h2>Sustainability</h2><p>We aim to reduce unnecessary packaging, present accurate product details and encourage considered purchasing decisions.</p></div></section>
        </div>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
