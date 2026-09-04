<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireLogin('../login.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken();
    $itemId = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

    if ($itemId && $quantity && $quantity >= 1) {
        $stmt = $pdo->prepare('SELECT p.name, p.stock FROM cart_items ci JOIN products p ON p.id = ci.product_id WHERE ci.id = ? AND ci.user_id = ?');
        $stmt->execute([$itemId, currentUserId()]);
        $product = $stmt->fetch();
        if ($product) {
            $safeQuantity = min($quantity, (int) $product['stock']);
            if ($safeQuantity < 1) {
                setFlash('error', $product['name'] . ' is currently out of stock. Remove it from your cart.');
            } else {
                $stmt = $pdo->prepare('UPDATE cart_items SET quantity = ? WHERE id = ? AND user_id = ?');
                $stmt->execute([$safeQuantity, $itemId, currentUserId()]);
                if ($safeQuantity !== $quantity) {
                    setFlash('error', 'Quantity adjusted to the available stock for ' . $product['name'] . '.');
                }
            }
        }
    }
}

header('Location: ../cart.php');
exit;
