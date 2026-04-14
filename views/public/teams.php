<?php require_once BASE_PATH . '/partials/header.php';
render_flash(); ?>

<h1 class="h3 mb-3">Teams</h1>

<form method="get" action="" class="mb-4">
    <div class="input-group" style="max-width: 420px;">
        <input type="search" name="q" class="form-control" placeholder="Search name or city…"
            value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
        <button class="btn btn-outline-secondary" type="submit">Search</button>
        <?php if ($search !== ''): ?>
            <a href="<?= BASE_URL ?>/pages/public/teams.php" class="btn btn-outline-secondary">Clear</a>
        <?php endif; ?>
    </div>
</form>

<?php if (empty($teams)): ?>
    <div class="alert alert-info">No teams found.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Team</th>
                    <th>Home City</th>
                    <th>Roster</th>
                    <th>Matches</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teams as $i => $t): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><a
                                href="<?= BASE_URL ?>/pages/public/team_detail.php?id=<?= (int) $t['team_id'] ?>"><?= htmlspecialchars($t['name'], ENT_QUOTES, 'UTF-8') ?></a>
                        </td>
                        <td><?= $t['home_city'] ? htmlspecialchars($t['home_city'], ENT_QUOTES, 'UTF-8') : '—' ?></td>
                        <td><span class="badge bg-primary"><?= (int) $t['roster_size'] ?></span></td>
                        <td><?= (int) $t['match_count'] ?></td>
                        <td><a href="<?= BASE_URL ?>/pages/public/team_detail.php?id=<?= (int) $t['team_id'] ?>"
                                class="btn btn-sm btn-outline-primary">View</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once BASE_PATH . '/partials/footer.php'; ?>