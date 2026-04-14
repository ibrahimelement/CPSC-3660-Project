<?php require_once BASE_PATH . '/partials/header.php';
render_flash(); ?>

<h1 class="h3 mb-3">Match History</h1>

<form method="get" action="" class="mb-4">
    <div class="row g-2 align-items-end">
        <div class="col-sm-5">
            <label for="season_id" class="form-label fw-semibold">Season</label>
            <select name="season_id" id="season_id" class="form-select">
                <option value="">— All seasons —</option>
                <?php foreach ($seasons as $s): ?>
                    <option value="<?= (int) $s['season_id'] ?>" <?= (int) $s['season_id'] === $selectedSeason ? 'selected' : '' ?>><?= htmlspecialchars($s['league'] . ' — ' . $s['name'], ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-sm-4">
            <label for="team_id" class="form-label fw-semibold">Team</label>
            <select name="team_id" id="team_id" class="form-select">
                <option value="">— All teams —</option>
                <?php foreach ($teams as $t): ?>
                    <option value="<?= (int) $t['team_id'] ?>" <?= (int) $t['team_id'] === $selectedTeam ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['name'], ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" type="submit">Filter</button>
            <a href="<?= BASE_URL ?>/pages/public/matches.php" class="btn btn-outline-secondary">Clear</a>
        </div>
    </div>
</form>

<?php if (empty($matches)): ?>
    <div class="alert alert-info">No matches found for the selected filters.</div>
<?php else: ?>
    <p class="text-muted small mb-2">Showing <?= count($matches) ?> most recent matches.</p>
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Home</th>
                    <th class="text-center">Score</th>
                    <th>Away</th>
                    <th>Venue</th>
                    <th>Season</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($matches as $m):
                    $hs = (int) $m['home_score'];
                    $as = (int) $m['away_score'];
                    if ($hs > $as)
                        $score = "<span class=\"text-success\">$hs</span> – $as";
                    elseif ($hs < $as)
                        $score = "$hs – <span class=\"text-success\">$as</span>";
                    else
                        $score = "$hs – $as";
                    ?>
                    <tr>
                        <td class="text-nowrap"><?= substr($m['match_date'], 0, 10) ?></td>
                        <td><a
                                href="<?= BASE_URL ?>/pages/public/team_detail.php?id=<?= (int) $m['home_team_id'] ?>"><?= htmlspecialchars($m['home_team'], ENT_QUOTES, 'UTF-8') ?></a>
                        </td>
                        <td class="text-center"><strong><?= $score ?></strong></td>
                        <td><a
                                href="<?= BASE_URL ?>/pages/public/team_detail.php?id=<?= (int) $m['away_team_id'] ?>"><?= htmlspecialchars($m['away_team'], ENT_QUOTES, 'UTF-8') ?></a>
                        </td>
                        <td class="text-muted small"><?= htmlspecialchars($m['venue'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-muted small">
                            <?= htmlspecialchars($m['league'] . ' · ' . $m['season_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once BASE_PATH . '/partials/footer.php'; ?>