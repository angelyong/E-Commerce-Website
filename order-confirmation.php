<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

requireLogin();

$orderId = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);
if (!$orderId) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT id, total, status, created_at FROM orders WHERE id = ? AND user_id = ?');
$stmt->execute([$orderId, currentUserId()]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT oi.quantity, oi.price, p.name FROM order_items oi JOIN products p ON p.id = oi.product_id WHERE oi.order_id = ?');
$stmt->execute([$orderId]);
$orderItems = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> ORDER PLACED | E-COMMERCE WEBSITE BY EDYODA </title>
    <!-- favicon -->
    <link rel="icon" href="https://yt3.ggpht.com/a/AGF-l78km1YyNXmF0r3-0CycCA0HLA_i6zYn_8NZEg=s900-c-k-c0xffffffff-no-rj-mo" type="image/gif" sizes="16x16">
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo asset('assets/css/main.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/order-confirmation.css'); ?>">
</head>
<body>
    <!-- HEADER -->
    <?php include 'includes/header.php'; ?>

    <!-- ORDER PLACED -->
    <div id="orderContainer">
        <div id="check"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M8 12.5l2.5 2.5L16 9.5"></path></svg></div>

        <div id="aboutCheck">
            <h1> Order Placed Successfully! </h1>
            <p> Order #<?php echo (int) $order['id']; ?> &middot; <?php echo htmlspecialchars(date('F j, Y g:i A', strtotime($order['created_at']))); ?> </p>

            <div id="orderSummary">
                <table id="orderItemsTable">
                    <thead>
                        <tr><th>Item</th><th>Qty</th><th>Price</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderItems as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo (int) $item['quantity']; ?></td>
                                <td>Rs <?php echo htmlspecialchars(number_format($item['price'], 2)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p id="orderTotal"> Total: Rs <?php echo htmlspecialchars(number_format($order['total'], 2)); ?> </p>
            </div>
        </div>
    </div>
        <!-- FOOTER -->
        <?php include 'includes/footer.php'; ?>
</body>
</html>
