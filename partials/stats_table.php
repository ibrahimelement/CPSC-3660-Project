<?php
$showTeam ??= false;
$showPosition ??= false;
$showRank ??= false;
?>
<div class="table-responsive">
    <table class="table table-hover table-bordered align-middle">
        <thead>
            <tr>
                <?php if ($showRank): ?>
                    <th>Rank</th><?php endif; ?>
                <th>Player</th>
                <?php if ($showPosition): ?>
                    <th>Pos</th><?php endif; ?>
                <?php if ($showTeam): ?>
                    <th>Team</th><?php endif; ?>
                <th>GP</th>
                <th>G</th>
                <th>A</th>
                <th class="table-primary">PTS</th>
                <th>PIM</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($statRows as $_i => $_r): ?>
                <tr<?= ($showRank && $_i < 3) ? ' class="table-warning"' : '' ?>>
                    <?php if ($showRank): ?>
                        <td><?= $_i === 0 ? '🥇' : ($_i === 1 ? '🥈' : ($_i === 2 ? '🥉' : $_i + 1)) ?></td><?php endif; ?>
                    <td><a
                            href="<?= BASE_URL ?>/pages/public/player_detail.php?id=<?= (int) $_r['player_id'] ?>"><?= htmlspecialchars($_r['first_name'] . ' ' . $_r['last_name'], ENT_QUOTES, 'UTF-8') ?></a>
                    </td>
                    <?php if ($showPosition): ?>
                        <td><span
                                class="badge bg-secondary"><?= htmlspecialchars($_r['position'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                        </td><?php endif; ?>
                    <?php if ($showTeam): ?>
                        <td><?= htmlspecialchars($_r['team_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td><?php endif; ?>
                    <td><?= (int) $_r['games_played'] ?></td>
                    <td><?= (int) $_r['goals'] ?></td>
                    <td><?= (int) $_r['assists'] ?></td>
                    <td class="table-primary fw-bold"><?= (int) $_r['points'] ?></td>
                    <td><?= (int) $_r['pim'] ?></td>
                    </tr>
                <?php endforeach; ?>
        </tbody>
    </table>
</div>