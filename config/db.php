<?php
declare(strict_types=1);

// ---------------------------------------------------------------------------
// Database & application configuration
//
// Values are read from environment variables first (set via .env + Docker
// Compose), then fall back to the defaults below for bare-metal deployments.
// To change settings for bare-metal / vcandle, set the env vars or edit
// the fallback strings here.
// ---------------------------------------------------------------------------

define('DB_HOST',    $_ENV['DB_HOST']    ?? getenv('DB_HOST')    ?: 'localhost');
define('DB_NAME',    $_ENV['DB_NAME']    ?? getenv('DB_NAME')    ?: 'icetrack');
define('DB_USER',    $_ENV['DB_USER']    ?? getenv('DB_USER')    ?: 'root');
define('DB_PASS',    $_ENV['DB_PASS']    ?? getenv('DB_PASS')    ?: '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Base URL (no trailing slash).
 * Examples:
 *   ''                    → localhost root  (Docker dev default)
 *   '/~username/icetrack' → vcandle shared hosting
 */
define('BASE_URL', $_ENV['BASE_URL'] ?? getenv('BASE_URL') ?: '');

/**
 * Absolute filesystem path to the project root directory.
 */
define('BASE_PATH', dirname(__FILE__, 2));

/**
 * Returns a shared PDO instance (lazy singleton).
 */
function get_db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            DB_HOST, DB_NAME, DB_CHARSET
        );
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }

    return $pdo;
}
