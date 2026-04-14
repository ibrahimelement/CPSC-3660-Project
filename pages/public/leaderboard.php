<?php
require_once __DIR__ . '/common.php';

// select the available seasons (all historical seasons as well)
$seasonsStmt = $db->prepare(
    'SELECT season_id, league, name
    FROM seasons
    ORDER BY name DESC, league'
);
$seasonsStmt->execute();
$seasons = $seasonsStmt->fetchAll();

// if no season is selected, use the very latest season
$selectedSeason = filter_input(INPUT_GET, 'season_id', FILTER_VALIDATE_INT) ?: null;
if (!$selectedSeason && !empty($seasons)) {
    // cast to int
    $selectedSeason = (int) $seasons[0]['season_id'];
}

$leaderboard = [];
$seasonLabel = '';

if ($selectedSeason) {

    // grab the last season label
    foreach ($seasons as $season) {
        if ((int) $season['season_id'] === $selectedSeason) {
            // present as leage . name
            $seasonLabel = $season['league'] . ' · ' . $season['name'];
            break;
        }
    }

    // grab top scorers for the selected season
    $stmtLb = $db->prepare(
        'SELECT p.player_id,
       p.first_name,
       p.last_name,
       p.position,
       t.name                                    AS team_name,
       COUNT(pms.match_id)                       AS games_played,
       # sum up goals, assists, and pim
       # using COALESCE to avoid adding NULL to the rolling sum
       COALESCE(SUM(pms.goals), 0)               AS goals,
       COALESCE(SUM(pms.assists), 0)             AS assists,
       COALESCE(SUM(pms.goals + pms.assists), 0) AS points,
       COALESCE(SUM(pms.pim), 0)                 AS pim
FROM player_match_stats pms
         INNER JOIN players p ON p.player_id = pms.player_id
         INNER JOIN matches m ON m.match_id = pms.match_id
         INNER JOIN teams t ON t.team_id = pms.team_id
# parameter to select the specific season
WHERE m.season_id = ?
# grouping by the specific player stats
GROUP BY p.player_id, p.first_name, p.last_name, p.position, t.name
# only include players who have actually played
HAVING games_played > 0
# multi-column sort on points -> goals -> assists
ORDER BY points DESC, goals DESC, assists DESC
# we want max 50 players for this query
LIMIT 50
         '
    );

    # run this query using the selected season passed in (prepared statement)
    $stmtLb->execute([$selectedSeason]);

    # grab all results (capped at 50 results though)
    $leaderboard = $stmtLb->fetchAll();
}

$pageTitle = 'Season Leaderboard';
require BASE_PATH . '/views/public/leaderboard.php';
