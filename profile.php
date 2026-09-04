<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

requireLogin();

$stmt = $pdo->prepare('SELECT name, email, role, created_at FROM users WHERE id = ?');
$stmt->execute([currentUserId()]);
$user = $stmt->fetch();

$stmt = $pdo->prepare('SELECT o.id, o.total, o.status, o.created_at, COALESCE(SUM(oi.quantity), 0) AS item_count FROM orders o LEFT JOIN order_items oi ON oi.order_id = o.id WHERE o.user_id = ? GROUP BY o.id, o.total, o.status, o.created_at ORDER BY o.created_at DESC');
$stmt->execute([currentUserId()]);
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My account | SHOPLANE</title>
    <link rel="stylesheet" href="<?php echo asset('assets/css/main.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/auth.css'); ?>">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main id="profileContainer">
        <h1> My Account </h1>
        <section class="profile-details" aria-label="Account details">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Member since:</strong> <?php echo htmlspecialchars(date('F j, Y', strtotime($user['created_at']))); ?></p>
            <a href="logout.php" id="logoutBtn"> Logout </a>
        </section>

        <section class="order-history" aria-labelledby="orderHistoryHeading">
            <h2 id="orderHistoryHeading">Order history</h2>
            <?php if ($orders): ?>
                <div class="order-list">
                    <?php foreach ($orders as $order): ?>
                        <a class="order-row" href="order-confirmation.php?order_id=<?php echo (int) $order['id']; ?>">
                            <span><strong>Order #<?php echo (int) $order['id']; ?></strong><small><?php echo htmlspecialchars(date('F j, Y', strtotime($order['created_at']))); ?></small></span>
                            <span><?php echo (int) $order['item_count']; ?> item(s)</span>
                            <span>Rs <?php echo htmlspecialchars(number_format($order['total'], 2)); ?></span>
                            <span class="order-status"><?php echo htmlspecialchars(ucfirst($order['status'])); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="account-empty"><p>You have not placed an order yet.</p><a href="products.php">Start shopping</a></div>
            <?php endif; ?>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
