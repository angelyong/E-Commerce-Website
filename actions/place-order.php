<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireLogin('../login.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../cart.php');
    exit;
}

verifyCsrfToken();

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare('SELECT ci.product_id, ci.quantity, p.name, p.price, p.stock FROM cart_items ci JOIN products p ON p.id = ci.product_id WHERE ci.user_id = ? ORDER BY p.id FOR UPDATE');
    $stmt->execute([currentUserId()]);
    $items = $stmt->fetchAll();

    if (!$items) {
        $pdo->rollBack();
        header('Location: ../cart.php');
        exit;
    }

    $total = 0;
    foreach ($items as $item) {
        if ((int) $item['stock'] < (int) $item['quantity']) {
            throw new DomainException('Not enough stock is available for ' . $item['name'] . '. Please update your cart.');
        }
        $total += (float) $item['price'] * (int) $item['quantity'];
    }

    $stmt = $pdo->prepare('INSERT INTO orders (user_id, total, status) VALUES (?, ?, ?)');
    $stmt->execute([currentUserId(), $total, 'placed']);
    $orderId = (int) $pdo->lastInsertId();

    $insertItem = $pdo->prepare('INSERT INTO order_items (order_id, product_id, product_name, quantity, price) VALUES (?, ?, ?, ?, ?)');
    $decreaseStock = $pdo->prepare('UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?');
    foreach ($items as $item) {
        $insertItem->execute([$orderId, $item['product_id'], $item['name'], $item['quantity'], $item['price']]);
        $decreaseStock->execute([$item['quantity'], $item['product_id'], $item['quantity']]);
        if ($decreaseStock->rowCount() !== 1) {
            throw new DomainException('Stock changed while the order was being placed. Please review your cart.');
        }
    }

    $stmt = $pdo->prepare('DELETE FROM cart_items WHERE user_id = ?');
    $stmt->execute([currentUserId()]);

    $pdo->commit();
    header('Location: ../order-confirmation.php?order_id=' . $orderId);
    exit;
} catch (Throwable $error) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $message = $error instanceof DomainException
        ? $error->getMessage()
        : 'The order could not be placed. Please try again.';
    setFlash('error', $message);
    header('Location: ../cart.php');
    exit;
}
