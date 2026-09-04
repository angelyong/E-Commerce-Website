<?php
require_once __DIR__ . '/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function currentUserId()
{
    return $_SESSION['user_id'] ?? null;
}

function currentUserName()
{
    return $_SESSION['user_name'] ?? null;
}

function currentUserRole()
{
    return $_SESSION['user_role'] ?? null;
}

function requireLogin($redirectTo = 'login.php')
{
    if (!isLoggedIn()) {
        $returnUrl = $_SERVER['REQUEST_URI'] ?? '';
        $separator = strpos($redirectTo, '?') === false ? '?' : '&';
        header('Location: ' . $redirectTo . $separator . 'redirect=' . urlencode($returnUrl));
        exit;
    }
}

function sanitizeRedirectPath($path, $default = 'index.php')
{
    if (!is_string($path) || $path === '' || $path[0] !== '/') {
        return $default;
    }
    if (strpos($path, '//') === 0 || preg_match('#^[a-zA-Z][a-zA-Z0-9+.-]*://#', $path)) {
        return $default;
    }
    return $path;
}

function isAdmin()
{
    return currentUserRole() === 'admin';
}

function requireAdmin($redirectTo = 'login.php')
{
    requireLogin($redirectTo);
    if (!isAdmin()) {
        http_response_code(403);
        exit('Forbidden: admin access only.');
    }
}

function csrfToken()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfField()
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') . '">';
}

function verifyCsrfToken()
{
    $submittedToken = $_POST['csrf_token'] ?? '';
    if (!is_string($submittedToken) || !hash_equals(csrfToken(), $submittedToken)) {
        http_response_code(403);
        exit('Your session token is invalid or expired. Please return to the previous page and try again.');
    }
}

function setFlash($type, $message)
{
    $_SESSION['flash_messages'][$type][] = $message;
}

function pullFlashMessages()
{
    $messages = $_SESSION['flash_messages'] ?? [];
    unset($_SESSION['flash_messages']);
    return $messages;
}
