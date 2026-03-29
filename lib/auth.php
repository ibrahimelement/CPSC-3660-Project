<!-- Common set of functions for managing auth across
 ADMIN and USER sessions.
  -->

<?php
declare(strict_types=1);

/**
 * Internal method to create a new session
 * using PHP provided functions.
 */
function _session_start(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start([
            'cookie_httponly' => true,
            'cookie_samesite' => 'Lax',
        ]);
    }
}

/**
 * Extracts user attribute from the user
 * session.
 */
function current_user(): ?array
{
    _session_start();
    return $_SESSION['user'] ?? null;
}

/**
 * Checks if there's a current user session,
 * returns true or false respectively.
 */
function is_logged_in(): bool
{
    return current_user() !== null;
}

/**
 * Simply checks the `role` attribute
 * associated to the user session. Returns
 * true or false accordingly.
 */
function is_admin(): bool
{
    $user = current_user();
    return $user !== null && $user['role'] === 'admin';
}

/**
 * Helper method invoked to ensure that
 * the page requested is only accessed by
 * admins (security).
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
 * Starts the session, and adds attributes
 * required to the PHP session.
 */
function login_user(array $row): void
{
    _session_start();
    session_regenerate_id(true);
    $_SESSION['user'] = [
        'user_id' => (int) $row['user_id'],
        'email' => $row['email'],
        'display_name' => $row['display_name'],
        'role' => $row['role'],
    ];
}

/**
 * Deletes the user session
 */
function logout_user(): void
{
    _session_start();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $p['path'],
            $p['domain'],
            $p['secure'],
            $p['httponly']
        );
    }
    session_destroy();
}
