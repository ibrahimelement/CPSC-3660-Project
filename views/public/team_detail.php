<?php require_once BASE_PATH . '/partials/header.php';
render_flash(); ?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/pages/public/teams.php">Teams</a></li>
        <li class="breadcrumb-item active"><?= htmlspecialchars($team['name'], ENT_QUOTES, 'UTF-8') ?></li>
    </ol>
</nav>

<div class="card mb-4">
    <div class="card-body">
        <h1 class="h3 mb-1"><?= htmlspecialchars($team['name'], ENT_QUOTES, 'UTF-8') ?></h1>
        <?php if ($team['home_city']): ?>
            <p class="text-muted mb-2">📍 <?= htmlspecialchars($team['home_city'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <?php if ((int) $record['games_played'] > 0): ?>
            <div class="d-flex gap-3 mt-3">
                <?php foreach (['Wins' => ['text-success', $record['wins']], 'Losses' => ['text-danger', $record['losses']], 'Ties' => ['text-secondary', $record['ties']], 'Played' => ['', $record['games_played']]] as $lbl => [$cls, $v]): ?>
                    <div class="text-center">
                        <div class="fs-4 fw-bold <?= $cls ?>"><?= (int) $v ?></div>
                        <div class="small text-muted"><?= $lbl ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted mt-2">No matches recorded yet.</p>
        <?php endif; ?>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header fw-semibold">Current Roster</div>
            <div class="card-body p-0">
                <?php if (empty($roster)): ?>
                    <p class="p-3 text-muted mb-0">No active players on roster.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Player</th>
                                    <th>Pos</th>
                                    <th>Jersey</th>
                                    <th>Since</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($roster as $p): ?>
                                    <tr>
                                        <td><a
                                                href="<?= BASE_URL ?>/pages/public/player_detail.php?id=<?= (int) $p['player_id'] ?>"><?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name'], ENT_QUOTES, 'UTF-8') ?></a>
                                        </td>
                                        <td><span
                                                class="badge bg-secondary"><?= htmlspecialchars($p['position'], ENT_QUOTES, 'UTF-8') ?></span>
                                        </td>
                                        <td><?= $p['jersey_number'] !== null ? '#' . (int) $p['jersey_number'] : '—' ?></td>
                                        <td class="text-muted small">
                                            <?= htmlspecialchars($p['start_date'], ENT_QUOTES, 'UTF-8') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header fw-semibold">All-Time Top Scorers</div>
            <div class="card-body p-0">
                <?php if (empty($topScorers)): ?>
                    <p class="p-3 text-muted mb-0">No stats recorded yet.</p>
                <?php else: ?>
                    <?php $statRows = $topScorers;
                    require BASE_PATH . '/partials/stats_table.php'; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header fw-semibold">Recent Matches</div>
            <div class="card-body p-0">
                <?php if (empty($recentMatches)): ?>
                    <p class="p-3 text-muted mb-0">No matches recorded yet.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Match</th>
                                    <th>Score</th>
                                    <th>Venue</th>
                                    <th>Season</th>
                                    <th>Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentMatches as $m):
                                    $ts = ($m['home_team'] === $team['name']) ? $m['home_score'] : $m['away_score'];
                                    $os = ($m['home_team'] === $team['name']) ? $m['away_score'] : $m['home_score'];
                                    $res = $ts > $os ? '<span class="badge bg-success">W</span>' : ($ts < $os ? '<span class="badge bg-danger">L</span>' : '<span class="badge bg-secondary">T</span>');
                                    ?>
                                    <tr>
                                        <td class="text-nowrap"><?= substr($m['match_date'], 0, 10) ?></td>
                                        <td><?= htmlspecialchars($m['home_team'] . ' vs ' . $m['away_team'], ENT_QUOTES, 'UTF-8') ?>
                                        </td>
                                        <td><strong><?= (int) $m['home_score'] ?> – <?= (int) $m['away_score'] ?></strong></td>
                                        <td class="text-muted small"><?= htmlspecialchars($m['venue'], ENT_QUOTES, 'UTF-8') ?>
                                        </td>
                                        <td class="text-muted small">
                                            <?= htmlspecialchars($m['league'] . ' · ' . $m['season_name'], ENT_QUOTES, 'UTF-8') ?>
                                        </td>
                                        <td><?= $res ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/partials/footer.php'; ?>