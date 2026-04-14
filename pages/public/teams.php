<?php
require_once __DIR__ . '/common.php';

// get search term
$search = trim($_GET['q'] ?? '');
$like = '%' . $search . '%';

// return teams using subqueries and filters provided
$sql = 'SELECT t.team_id, t.name, t.home_city,
        (SELECT COUNT(*) FROM player_team_memberships
         WHERE team_id = t.team_id AND end_date IS NULL) AS roster_size,
        (SELECT COUNT(*) FROM matches
         WHERE home_team_id = t.team_id OR away_team_id = t.team_id) AS match_count
 FROM teams t';

if ($search !== '') {
    $sql .= ' WHERE t.name LIKE ? OR t.home_city LIKE ?';
}

$sql .= ' ORDER BY t.name';
$stmt = $db->prepare($sql);
$stmt->execute($search !== '' ? [$like, $like] : []);

// grab ALL returned results
$teams = $stmt->fetchAll();
$pageTitle = 'Teams';

// import the teams view
require BASE_PATH . '/views/public/teams.php';
