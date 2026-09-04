<?php
require_once __DIR__ . '/includes/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping help | SHOPLANE</title>
    <meta name="description" content="SHOPLANE shipping, returns, privacy, terms and cookie information.">
    <link rel="stylesheet" href="<?php echo asset('assets/css/main.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/info.css'); ?>">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <main class="info-page">
        <header class="info-hero"><span>Customer care</span><h1>How can we help?</h1><p>Find key information about ordering, delivery, returns and account data.</p></header>
        <div class="info-grid help-grid">
            <section id="shipping"><span>01</span><div><h2>Shipping</h2><p>Orders over Rs 75 qualify for free standard shipping. Delivery times depend on destination and are confirmed when your order is processed.</p></div></section>
            <section id="returns"><span>02</span><div><h2>Returns</h2><p>Return eligible unused items within 30 days. Contact us with your order number before sending an item back.</p><a href="contact.php">Start a return &rarr;</a></div></section>
            <section id="privacy"><span>03</span><div><h2>Privacy</h2><p>Account, cart, wishlist and order information is used only to provide the store experience. Passwords are stored as secure hashes, never as plain text.</p></div></section>
            <section id="terms"><span>04</span><div><h2>Terms</h2><p>Product availability and prices are confirmed when an order is placed. Orders cannot exceed the available stock.</p></div></section>
            <section id="cookies"><span>05</span><div><h2>Cookies</h2><p>The site uses a session cookie to keep users signed in and to protect account-specific actions.</p></div></section>
            <section><span>06</span><div><h2>Still need help?</h2><p>Send the support team a message and include relevant order details.</p><a href="contact.php">Contact support &rarr;</a></div></section>
        </div>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
