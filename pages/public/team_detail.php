<?php
require_once __DIR__ . '/common.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header('Location: ' . BASE_URL . '/pages/public/teams.php');
    exit;
}

// grab team record
$stmt = $db->prepare('SELECT * FROM teams WHERE team_id = ?');
$stmt->execute([$id]);
$team = $stmt->fetch();

// 404: if no team then flash error
if (!$team) {
    set_flash('error', 'Team not found.');
    // obviously consider a security exception here, but ignoring for this
    // assignment
    header('Location: ' . BASE_URL . '/pages/public/teams.php');
    exit;
}

// get all players currently on the team
$stmtRoster = $db->prepare(
    'SELECT p.player_id, p.first_name, p.last_name, p.position, p.jersey_number, m.start_date
FROM player_team_memberships m
    # join membership on player
    INNER JOIN players p ON p.player_id = m.player_id
    # end_date must not be null, otherwise, this is a previous player
WHERE m.team_id = ? AND m.end_date IS NULL
ORDER BY p.last_name, p.first_name'
);

$stmtRoster->execute([$id]);
$roster = $stmtRoster->fetchAll();

// same query as `standings.php`, we're getting the WINS/LOSS/TIE ratio for specific teams
// and selecting from a CTE
$stmtRecord = $db->prepare(
    'WITH teamMatches AS (
    SELECT home_score AS goals_for, away_score AS goals_against
    FROM matches WHERE home_team_id = ?
    UNION ALL
    SELECT away_score, home_score
    FROM matches WHERE away_team_id = ?
)
SELECT COUNT(*) AS games_played,
    SUM(goals_for > goals_against) AS wins,
    SUM(goals_for < goals_against) AS losses,
    SUM(goals_for = goals_against) AS ties
FROM teamMatches'
);

$stmtRecord->execute([$id, $id]);
$record = $stmtRecord->fetch();

// grab recent matches (last 8)
$stmtMatches = $db->prepare(
    'SELECT m.match_id,
       m.match_date,
       m.venue,
       m.home_score,
       m.away_score,
       ht.name AS home_team,
       at.name AS away_team,
       s.league,
       s.name  AS season_name
FROM matches m
         # enrich match data with team information
         JOIN teams ht ON ht.team_id = m.home_team_id
         JOIN teams at ON at.team_id = m.away_team_id
         JOIN seasons s ON s.season_id = m.season_id
WHERE m.home_team_id = ?
   OR m.away_team_id = ?
ORDER BY m.match_date DESC
# we just limit up to 8
LIMIT 8'
);
$stmtMatches->execute([$id, $id]);
$recentMatches = $stmtMatches->fetchAll();

// select the top 10 scorers on this team,
// we don't check membership.endDate != null 
// so this will include all historical players as well
// (players that have left the team and are no longer members)
$stmtScorers = $db->prepare(
    'SELECT p.player_id,
       p.first_name,
       p.last_name,
       # aggregate the goals and assits for the player
       COUNT(*)                     AS games_played,
       SUM(pms.goals)               AS goals,
       SUM(pms.assists)             AS assists,
       SUM(pms.goals + pms.assists) AS points,
       SUM(pms.pim)                 AS pim
FROM player_match_stats pms
         JOIN players p ON p.player_id = pms.player_id
# grabbing all players for the provided team id
WHERE pms.team_id = ?
# grouping by the specific player
GROUP BY p.player_id, p.first_name, p.last_name
ORDER BY points DESC, goals DESC
LIMIT 10'
);

$stmtScorers->execute([$id]);
$topScorers = $stmtScorers->fetchAll();

$pageTitle = $team['name'];
require BASE_PATH . '/views/public/team_detail.php';
