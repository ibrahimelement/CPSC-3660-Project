<?php
require_once __DIR__ . '/common.php';

// exctract ID form the ID url parameter
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// if we don't have an id, redirect to the main page
if (!$id || $id < 1) {
    header('Location: ' . BASE_URL . '/pages/public/players.php');
    exit;
}

// grab player record
$stmt = $db->prepare('SELECT * FROM players WHERE player_id = ?');
$stmt->execute([$id]);
$player = $stmt->fetch();

// 404: not found, the provided ID is not valid
if (!$player) {
    // ideally we should log an exception here
    // as a case for potential security concerns
    set_flash('error', 'Player not found.');
    header('Location: ' . BASE_URL . '/pages/public/players.php');
    exit;
}

// select the latest team history
$stmtMem = $db->prepare(
    'SELECT m.start_date,
       m.end_date,
       m.note,
       t.name AS team_name,
       t.home_city
# grab data from player membership table
FROM player_team_memberships m
         # get all the teams the player was on historically
         INNER JOIN teams t ON t.team_id = m.team_id
WHERE m.player_id = ?
# we want to know the latest team (potentially the current one)
ORDER BY m.start_date DESC'
);
$stmtMem->execute([$id]);
$memberships = $stmtMem->fetchAll();

// SUM up ALL data for this user that's inside of the player_match_stats
// collection, this table has all the processed information
$stmtTotals = $db->prepare(
    'SELECT COUNT(*)                     AS games_played,
       COALESCE(SUM(goals), 0)           AS total_goals,
       COALESCE(SUM(assists), 0)         AS total_assists,
       COALESCE(SUM(goals + assists), 0) AS total_points,
       COALESCE(SUM(pim), 0)             AS total_pim
FROM player_match_stats
WHERE player_id = ?'
);

$stmtTotals->execute([$id]);
$totals = $stmtTotals->fetch();

// SELECT individual match stats lines, unline the above query
// this does not aggregate all results across all matches, so this
// is a nice view to see the individual stats (limited to 10 results though)
$stmtRecent = $db->prepare(
    'SELECT pms.goals,
       pms.assists,
       (pms.goals + pms.assists) AS points,
       pms.pim,
       m.match_date,
       m.home_score,
       m.away_score,
       m.venue,
       ht.name                   AS home_team,
       at.name                   AS away_team,
       pt.name                   AS player_team
FROM player_match_stats pms
         JOIN matches m ON m.match_id = pms.match_id
         JOIN teams ht ON ht.team_id = m.home_team_id
         JOIN teams at ON at.team_id = m.away_team_id
         JOIN teams pt ON pt.team_id = pms.team_id
WHERE pms.player_id = ?
ORDER BY m.match_date DESC
LIMIT 10'
);

$stmtRecent->execute([$id]);
$recent = $stmtRecent->fetchAll();

$fullName = $player['first_name'] . ' ' . $player['last_name'];
$pageTitle = $fullName;
require BASE_PATH . '/views/public/player_detail.php';
