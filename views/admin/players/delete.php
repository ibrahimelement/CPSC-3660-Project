<?php require_once BASE_PATH . '/partials/header.php';
render_flash(); ?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= BASE_URL ?>/pages/public/players.php">Players</a>
        </li>
        <li class="breadcrumb-item active">Delete</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card border-danger shadow-sm">
            <div class="card-header bg-danger text-white fw-semibold">Delete Player</div>
            <div class="card-body p-4">

                <?php if ($fkError !== null): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($fkError, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <p>You are about to permanently delete:</p>
                <p class="fs-5 fw-semibold">
                    <?= htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8') ?>
                    <span class="badge bg-secondary ms-1">
                        <?= htmlspecialchars($player['position'], ENT_QUOTES, 'UTF-8') ?>
                    </span>
                </p>

                <p class="text-muted small">This action cannot be undone.</p>

                <form method="post" action="?id=<?= (int) $id ?>">
                    <input type="hidden" name="id" value="<?= (int) $id ?>">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger">
                            Yes, Delete Player
                        </button>
                        <a href="<?= BASE_URL ?>/pages/public/players.php" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/partials/footer.php'; ?>