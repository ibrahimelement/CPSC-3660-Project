<?php
declare(strict_types=1);

// Requires BASE_PATH and BASE_URL to be defined (via config/db.php).

function _session_start(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start([
            'cookie_httponly' => true,
            'cookie_samesite' => 'Lax',
        ]);
    }
}

function current_user(): ?array
{
    _session_start();
    return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool
{
    return current_user() !== null;
}

function is_admin(): bool
{
    $user = current_user();
    return $user !== null && $user['role'] === 'admin';
}

/**
 * Redirect to login if the caller is not an authenticated admin.
 * Must be called before any output is sent.
 */
function require_admin(): void
{
    if (!is_admin()) {
        $next = urlencode($_SERVER['REQUEST_URI'] ?? '');
        header(
            'Location: ' . BASE_URL . '/pages/auth/login.php'
            . ($next !== '' ? '?next=' . $next : '')
        );
        exit;
    }
}

/**
 * Persist the authenticated user in the session.
 */
function login_user(array $row): void
{
    _session_start();
    session_regenerate_id(true);
    $_SESSION['user'] = [
        'user_id'      => (int) $row['user_id'],
        'email'        => $row['email'],
        'display_name' => $row['display_name'],
        'role'         => $row['role'],
    ];
}

/**
 * Destroy the current session completely.
 */
function logout_user(): void
{
    _session_start();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(
            session_name(), '', time() - 42000,
            $p['path'], $p['domain'], $p['secure'], $p['httponly']
        );
    }
    session_destroy();
}
