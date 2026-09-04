<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireLogin('../login.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken();
    $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);

    if ($productId) {
        $stmt = $pdo->prepare('SELECT id FROM products WHERE id = ?');
        $stmt->execute([$productId]);

        if ($stmt->fetch()) {
            $stmt = $pdo->prepare('SELECT id FROM wishlist_items WHERE user_id = ? AND product_id = ?');
            $stmt->execute([currentUserId(), $productId]);

            if (!$stmt->fetch()) {
                $stmt = $pdo->prepare('INSERT INTO wishlist_items (user_id, product_id) VALUES (?, ?)');
                $stmt->execute([currentUserId(), $productId]);
            }
        }
    }
}

header('Location: ../wishlist.php');
exit;
