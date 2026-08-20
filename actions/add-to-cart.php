<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireLogin('../login.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
    if (!$quantity || $quantity < 1) {
        $quantity = 1;
    }

    if ($productId) {
        $stmt = $pdo->prepare('SELECT id FROM products WHERE id = ?');
        $stmt->execute([$productId]);

        if ($stmt->fetch()) {
            $stmt = $pdo->prepare('SELECT id, quantity FROM cart_items WHERE user_id = ? AND product_id = ?');
            $stmt->execute([currentUserId(), $productId]);
            $existing = $stmt->fetch();

            if ($existing) {
                $stmt = $pdo->prepare('UPDATE cart_items SET quantity = ? WHERE id = ?');
                $stmt->execute([$existing['quantity'] + $quantity, $existing['id']]);
            } else {
                $stmt = $pdo->prepare('INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)');
                $stmt->execute([currentUserId(), $productId, $quantity]);
            }
        }
    }
}

header('Location: ../cart.php');
exit;
