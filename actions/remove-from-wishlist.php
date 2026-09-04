<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireLogin('../login.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken();
    $itemId = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);
    if ($itemId) {
        $stmt = $pdo->prepare('DELETE FROM wishlist_items WHERE id = ? AND user_id = ?');
        $stmt->execute([$itemId, currentUserId()]);
    }
}

header('Location: ../wishlist.php');
exit;
