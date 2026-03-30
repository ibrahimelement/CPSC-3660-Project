<?php
declare(strict_types=1);

// import libraries
require_once __DIR__ . '/config/db.php';
require_once BASE_PATH . '/lib/auth.php';
require_once BASE_PATH . '/lib/flash.php';

// ensure cookie is added
_session_start();

$pageTitle = 'Home';

// import header contents (boostrap)
require_once BASE_PATH . '/partials/header.php';

// render flash banner (for future events)
render_flash();
?>

<div class="row justify-content-center">
  <div class="col-md-8 text-center py-5">
    <h1 class="display-4 fw-bold">&#x1F3D2; IceTrack</h1>
    <p class="lead text-muted">
      Hockey standings, player lookup, team standing!
    </p>
    <div class="mt-4 d-flex gap-3 justify-content-center flex-wrap">
      <a href="<?= BASE_URL ?>/pages/public/players.php" class="btn btn-primary btn-lg">
        Search Players
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