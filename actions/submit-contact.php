<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../contact.php');
    exit;
}

verifyCsrfToken();

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

$errors = [];
if ($name === '') {
    $errors[] = 'Name is required.';
} elseif (strlen($name) > 100) {
    $errors[] = 'Name must be 100 characters or fewer.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'A valid email is required.';
} elseif (strlen($email) > 150) {
    $errors[] = 'Email must be 150 characters or fewer.';
}
if (strlen($subject) > 200) {
    $errors[] = 'Subject must be 200 characters or fewer.';
}
if ($message === '') {
    $errors[] = 'Message is required.';
} elseif (strlen($message) > 5000) {
    $errors[] = 'Message must be 5,000 characters or fewer.';
}

if ($errors) {
    $_SESSION['contact_errors'] = $errors;
    $_SESSION['contact_old'] = [
        'name' => $name,
        'email' => $email,
        'subject' => $subject,
        'message' => $message,
    ];
    header('Location: ../contact.php');
    exit;
}

$stmt = $pdo->prepare('INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)');
$stmt->execute([$name, $email, $subject, $message]);

$_SESSION['contact_success'] = true;
header('Location: ../contact.php');
exit;
