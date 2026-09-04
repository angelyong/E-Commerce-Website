<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireAdmin('../login.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken();
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
        $stmt->execute([$id]);
    }
}

header('Location: index.php');
exit;
