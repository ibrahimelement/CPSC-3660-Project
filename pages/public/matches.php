<?php
require_once __DIR__ . '/common.php';

// grab filter options
$seasonsStmt = $db->prepare(
    'SELECT season_id, league, name
    FROM seasons
    ORDER BY name DESC, league'
);

$seasonsStmt->execute();
$seasons = $seasonsStmt->fetchAll();

$teamsStmt = $db->prepare(
    'SELECT team_id, name
    FROM teams
    ORDER BY name'
);

$teamsStmt->execute();
$teams = $teamsStmt->fetchAll();

$selectedSeason = filter_input(INPUT_GET, 'season_id', FILTER_VALIDATE_INT) ?: null;
$selectedTeam = filter_input(INPUT_GET, 'team_id', FILTER_VALIDATE_INT) ?: null;

$sql = 'SELECT m.match_id,
       m.match_date,
       m.venue,
       m.home_score,
       m.away_score,
       ht.team_id AS home_team_id,
       ht.name    AS home_team,
       at.team_id AS away_team_id,
       at.name    AS away_team,
       s.league,
       s.name     AS season_name
FROM matches m
         # join home_team, away_team, and season using recorded
         # information in the matches table
         INNER JOIN teams ht ON ht.team_id = m.home_team_id
         INNER JOIN teams at ON at.team_id = m.away_team_id
         INNER JOIN seasons s ON s.season_id = m.season_id
# we are using WHERE true, so we can add conditionals after!
WHERE true AND m.home_score IS NOT NULL';

$params = [];

// if selected season, add that filter
if ($selectedSeason) {
    $sql .= ' AND m.season_id IN (?)';
    $params[] = $selectedSeason;
}

// add selected (home team / away team) filters
if ($selectedTeam) {
    $sql .= ' AND (m.home_team_id = ? OR m.away_team_id = ?)';
    $params[] = $selectedTeam;
    $params[] = $selectedTeam;
}

// add limit
$sql .= ' ORDER BY m.match_date DESC LIMIT 100';

$stmt = $db->prepare($sql);
$stmt->execute($params);
$matches = $stmt->fetchAll();

$pageTitle = 'Match History';
require BASE_PATH . '/views/public/matches.php';
