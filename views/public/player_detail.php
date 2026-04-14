<?php require_once BASE_PATH . '/partials/header.php';
render_flash(); ?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/pages/public/players.php">Players</a></li>
        <li class="breadcrumb-item active"><?= htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8') ?></li>
    </ol>
</nav>

<?php if (is_admin()): ?>
    <div class="mb-3 d-flex gap-2">
        <a href="<?= BASE_URL ?>/pages/admin/players/edit.php?id=<?= (int) $player['player_id'] ?>"
            class="btn btn-outline-warning btn-sm">Edit Player</a>
        <a href="<?= BASE_URL ?>/pages/admin/players/delete.php?id=<?= (int) $player['player_id'] ?>"
            class="btn btn-outline-danger btn-sm">Delete Player</a>
    </div>
<?php endif; ?>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header fw-semibold">Player Profile</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5">Name</dt>
                    <dd class="col-7"><?= htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8') ?></dd>
                    <dt class="col-5">Position</dt>
                    <dd class="col-7"><span
                            class="badge bg-secondary"><?= htmlspecialchars($player['position'], ENT_QUOTES, 'UTF-8') ?></span>
                    </dd>
                    <dt class="col-5">Jersey</dt>
                    <dd class="col-7">
                        <?= $player['jersey_number'] !== null ? '#' . (int) $player['jersey_number'] : '—' ?></dd>
                    <dt class="col-5">Level</dt>
                    <dd class="col-7">
                        <?= $player['level'] ? htmlspecialchars($player['level'], ENT_QUOTES, 'UTF-8') : '—' ?></dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header fw-semibold">Career Totals</div>
            <div class="card-body">
                <?php if ((int) $totals['games_played'] === 0): ?>
                    <p class="text-muted mb-0">No match statistics recorded yet.</p>
                <?php else: ?>
                    <div class="row text-center g-3">
                        <?php foreach (['GP' => $totals['games_played'], 'G' => $totals['total_goals'], 'A' => $totals['total_assists'], 'PTS' => $totals['total_points'], 'PIM' => $totals['total_pim']] as $label => $val): ?>
                            <div class="col">
                                <div class="border rounded p-2">
                                    <div class="fs-4 fw-bold"><?= (int) $val ?></div>
                                    <div class="small text-muted"><?= $label ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header fw-semibold">Team History</div>
    <div class="card-body p-0">
        <?php if (empty($memberships)): ?>
            <p class="text-muted p-3 mb-0">No team memberships on record.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Team</th>
                            <th>City</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($memberships as $m): ?>
                            <tr>
                                <td><?= htmlspecialchars($m['team_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($m['home_city'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($m['start_date'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= $m['end_date'] ? htmlspecialchars($m['end_date'], ENT_QUOTES, 'UTF-8') : '<span class="badge bg-success">Current</span>' ?>
                                </td>
                                <td><?= $m['note'] ? htmlspecialchars($m['note'], ENT_QUOTES, 'UTF-8') : '—' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($recent)): ?>
    <div class="card">
        <div class="card-header fw-semibold">Recent Match Stats (last 10)</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Match</th>
                            <th>Team</th>
                            <th>G</th>
                            <th>A</th>
                            <th>PTS</th>
                            <th>PIM</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent as $r): ?>
                            <tr>
                                <td><?= date('Y-m-d', strtotime($r['match_date'])) ?></td>
                                <td><?= htmlspecialchars($r['home_team'] . ' vs ' . $r['away_team'], ENT_QUOTES, 'UTF-8') ?>
                                </td>
                                <td><?= htmlspecialchars($r['player_team'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= (int) $r['goals'] ?></td>
                                <td><?= (int) $r['assists'] ?></td>
                                <td><strong><?= (int) $r['points'] ?></strong></td>
                                <td><?= (int) $r['pim'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require_once BASE_PATH . '/partials/footer.php'; ?>