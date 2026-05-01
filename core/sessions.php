<?php
declare(strict_types=1);

ini_set('display_errors','1');
error_reporting(E_ALL);

$isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

ini_set('session.use_cookies','1');
ini_set('session.cookie_httponly','1');
ini_set('session.cookie_secure', $isHttps ? '1' : '0');
ini_set('session.cookie_samesite','Lax');

/* Force our cookie name early so nothing creates PHPSESSID */
ini_set('session.name', 'sportsclub_session');
session_name('sportsclub_session');

/* Proactively delete any legacy PHPSESSID cookie the browser still has */
if (isset($_COOKIE['PHPSESSID']) && session_name() !== 'PHPSESSID') {
    setcookie('PHPSESSID', '', time() - 42000, '/');
}

/* Set cookie params and start the session once */
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'domain'   => '',
    'secure'   => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax',
]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ---- Start the session once ---- */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ==================== Helpers ==================== */

function isLoggedIn(): bool {
    return isset($_SESSION['user']);
}

function getLoggedInUser(): ?array {
    return $_SESSION['user'] ?? null;
}

function getUserRole(): ?int {
    return isset($_SESSION['user']['role_id']) ? (int)$_SESSION['user']['role_id'] : null;
}

/**
 * Require login; redirect if not authenticated.
 * Tip: If your project lives in a subfolder, use an absolute path from web root.
 */
function requireLogin(string $redirect = '/Indoor%20sports%20club/pages/login.php'): void {
    if (!isLoggedIn()) {
        header('Location: ' . $redirect);
        exit();
    }
}

/**
 * Optional convenience if you want to call it from the login controller:
 * loginUser($id, $roleId, $email, $fullName)
 */
function loginUser(int $id, int $roleId, ?string $email = null, ?string $fullName = null): void {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    session_regenerate_id(true); // prevent fixation + force fresh Set-Cookie
    $_SESSION['user'] = [
        'id'       => $id,
        'role_id'  => $roleId,
        'email'    => $email ?? '',
        'full_name'=> $fullName ?? '',
    ];
    // session_write_close(); // uncomment if you want to immediately release the lock
}

/**
 * Destroy session and clear cookies.
 */
function logoutUser(string $redirect = '/Indoor%20sports%20club/pages/login.php'): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        // Clear the active session cookie
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        // Also clear any legacy PHPSESSID if it exists (from before you renamed sessions)
        if (isset($_COOKIE['PHPSESSID']) && session_name() !== 'PHPSESSID') {
            setcookie('PHPSESSID', '', time() - 42000, '/');
        }
    }

    session_destroy();
    header('Location: ' . $redirect);
    exit();
}