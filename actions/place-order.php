<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireLogin('../login.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../cart.php');
    exit;
}

$stmt = $pdo->prepare('SELECT ci.product_id, ci.quantity, p.price FROM cart_items ci JOIN products p ON p.id = ci.product_id WHERE ci.user_id = ?');
$stmt->execute([currentUserId()]);
$items = $stmt->fetchAll();

if (!$items) {
    header('Location: ../cart.php');
    exit;
}

$total = 0;
foreach ($items as $item) {
    $total += $item['price'] * $item['quantity'];
}

$pdo->beginTransaction();

$stmt = $pdo->prepare('INSERT INTO orders (user_id, total, status) VALUES (?, ?, ?)');
$stmt->execute([currentUserId(), $total, 'placed']);
$orderId = (int) $pdo->lastInsertId();

$stmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
foreach ($items as $item) {
    $stmt->execute([$orderId, $item['product_id'], $item['quantity'], $item['price']]);
}

$stmt = $pdo->prepare('DELETE FROM cart_items WHERE user_id = ?');
$stmt->execute([currentUserId()]);

$pdo->commit();

header('Location: ../order-confirmation.php?order_id=' . $orderId);
exit;
