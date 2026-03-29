<?php
declare(strict_types=1);

require_once __DIR__ . '/config/db.php';
require_once BASE_PATH . '/lib/auth.php';
require_once BASE_PATH . '/lib/flash.php';

_session_start();

$page_title = 'Home';
require_once BASE_PATH . '/partials/header.php';
render_flash();
?>

<div class="row justify-content-center">
  <div class="col-md-8 text-center py-5">
    <h1 class="display-4 fw-bold">&#x1F3D2; IceTrack</h1>
    <p class="lead text-muted">
      Hockey standings, player statistics, team performance, and match history.
    </p>
    <div class="mt-4 d-flex gap-3 justify-content-center flex-wrap">
      <a href="<?= BASE_URL ?>/pages/public/players.php" class="btn btn-primary btn-lg">
        Browse Players
      </a>
      <?php if (!is_logged_in()): ?>
        <a href="<?= BASE_URL ?>/pages/auth/login.php" class="btn btn-outline-secondary btn-lg">
          Admin Login
        </a>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require_once BASE_PATH . '/partials/footer.php'; ?>
