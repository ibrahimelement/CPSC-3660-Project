<?php require_once BASE_PATH . '/partials/header.php';
render_flash(); ?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= BASE_URL ?>/pages/public/players.php">Players</a>
        </li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold">
                Edit Player — <?= htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8') ?>
            </div>
            <div class="card-body p-4">
                <form method="post" action="?id=<?= (int) $id ?>" novalidate>

                    <?php require BASE_PATH . '/partials/player_form.php'; ?>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-warning">Update Player</button>
                        <a href="<?= BASE_URL ?>/pages/public/players.php" class="btn btn-outline-secondary">Cancel</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/partials/footer.php'; ?>