<?php
declare(strict_types=1);

/**
 * Set a flash message to show the user.
 */
function set_flash(string $type, string $message): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['_flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Displays the flash message.
 */
function render_flash(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['_flash'])) {
        return;
    }

    $flash = $_SESSION['_flash'];
    unset($_SESSION['_flash']);

    $bsType = ($flash['type'] === 'error') ? 'danger' : $flash['type'];
    $msg = htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8');

    echo <<<HTML
<div class="alert alert-{$bsType} alert-dismissible fade show" role="alert">
  {$msg}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

HTML;
}
