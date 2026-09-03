<?php
require_once __DIR__ . '/functions.php';

if (session_status() === PHP_SESSION_NONE) {
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
