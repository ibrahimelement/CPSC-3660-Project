<?php require_once BASE_PATH . '/partials/header.php';
render_flash(); ?>

<h1 class="h3 mb-3">Season Leaderboard</h1>

<?php require_once BASE_PATH . '/partials/season_picker.php'; ?>

<?php if (empty($seasons)): ?>
    <div class="alert alert-info">No seasons found.</div>
<?php elseif (empty($leaderboard)): ?>
    <div class="alert alert-info">No stats recorded for this season yet.</div>
<?php else: ?>
    <h2 class="h5 mb-3 text-muted"><?= htmlspecialchars($seasonLabel, ENT_QUOTES, 'UTF-8') ?></h2>
    <?php $statRows = $leaderboard;
    $showRank = true;
    $showPosition = true;
    $showTeam = true;
    require BASE_PATH . '/partials/stats_table.php'; ?>
    <p class="text-muted small">Showing top <?= count($leaderboard) ?> scorers. Sorted by points, then goals.</p>
<?php endif; ?>

<?php require_once BASE_PATH . '/partials/footer.php'; ?>