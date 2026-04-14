<?php require_once BASE_PATH . '/partials/header.php';
render_flash(); ?>

<h1 class="h3 mb-3">Standings</h1>

<?php require_once BASE_PATH . '/partials/season_picker.php'; ?>

<?php if (empty($seasons)): ?>
    <div class="alert alert-info">No seasons found.</div>
<?php elseif (empty($standings)): ?>
    <div class="alert alert-info">No match data recorded for this season yet.</div>
<?php else: ?>
    <h2 class="h5 mb-3 text-muted"><?= htmlspecialchars($seasonLabel, ENT_QUOTES, 'UTF-8') ?></h2>
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Team</th>
                    <th>GP</th>
                    <th>W</th>
                    <th>L</th>
                    <th>T</th>
                    <th>GF</th>
                    <th>GA</th>
                    <th>DIFF</th>
                    <th class="table-primary">PTS</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($standings as $i => $row):
                    $diff = (int) $row['goals_for'] - (int) $row['goals_against'];
                    $diffClass = $diff > 0 ? 'text-success' : ($diff < 0 ? 'text-danger' : 'text-muted'); ?>
                    <tr<?= $i === 0 ? ' class="table-warning"' : '' ?>>
                        <td><?= $i + 1 ?></td>
                        <td><a
                                href="<?= BASE_URL ?>/pages/public/team_detail.php?id=<?= (int) $row['team_id'] ?>"><?= htmlspecialchars($row['team_name'], ENT_QUOTES, 'UTF-8') ?></a>
                        </td>
                        <td><?= (int) $row['games_played'] ?></td>
                        <td><?= (int) $row['wins'] ?></td>
                        <td><?= (int) $row['losses'] ?></td>
                        <td><?= (int) $row['ties'] ?></td>
                        <td><?= (int) $row['goals_for'] ?></td>
                        <td><?= (int) $row['goals_against'] ?></td>
                        <td class="<?= $diffClass ?> fw-semibold"><?= $diff > 0 ? '+' . $diff : $diff ?></td>
                        <td class="table-primary fw-bold"><?= (int) $row['points'] ?></td>
                        </tr>
                    <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <p class="text-muted small">Points: 2 for a win, 1 for a tie, 0 for a loss. Sorted by PTS → W → GF.</p>
<?php endif; ?>

<?php require_once BASE_PATH . '/partials/footer.php'; ?>