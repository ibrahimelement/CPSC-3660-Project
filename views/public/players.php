<?php require_once BASE_PATH . '/partials/header.php';
render_flash(); ?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h3 mb-0">Players</h1>
  <?php if (is_admin()): ?>
    <a href="<?= BASE_URL ?>/pages/admin/players/create.php" class="btn btn-success btn-sm">
      + Add Player
    </a>
  <?php endif; ?>
</div>

<form method="get" action="" class="mb-4">
  <div class="input-group" style="max-width: 420px;">
    <input type="search" name="q" class="form-control" placeholder="Search name or position…"
      value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
    <button class="btn btn-outline-secondary" type="submit">Search</button>
    <?php if ($search !== ''): ?>
      <a href="<?= BASE_URL ?>/pages/public/players.php" class="btn btn-outline-secondary">Clear</a>
    <?php endif; ?>
  </div>
</form>

<?php if (empty($players)): ?>
  <div class="alert alert-info">No players found.</div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table table-hover table-bordered align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Position</th>
          <th>Jersey</th>
          <th>Level</th>
          <th>Current Team</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($players as $i => $p): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td>
              <?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name'], ENT_QUOTES, 'UTF-8') ?>
            </td>
            <td>
              <span class="badge bg-secondary">
                <?= htmlspecialchars($p['position'], ENT_QUOTES, 'UTF-8') ?>
              </span>
            </td>
            <td>
              <?= $p['jersey_number'] !== null ? '#' . (int) $p['jersey_number'] : '—' ?>
            </td>
            <td>
              <?= $p['level'] ? htmlspecialchars($p['level'], ENT_QUOTES, 'UTF-8') : '—' ?>
            </td>
            <td>
              <?= $p['team_name']
                ? htmlspecialchars($p['team_name'], ENT_QUOTES, 'UTF-8')
                : '<span class="text-muted">—</span>' ?>
            </td>
            <td>
              <?php if (is_admin()): ?>
                <a href="<?= BASE_URL ?>/pages/admin/players/edit.php?id=<?= (int) $p['player_id'] ?>"
                  class="btn btn-sm btn-outline-warning">Edit</a>
                <a href="<?= BASE_URL ?>/pages/admin/players/delete.php?id=<?= (int) $p['player_id'] ?>"
                  class="btn btn-sm btn-outline-danger">Delete</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php require_once BASE_PATH . '/partials/footer.php'; ?>