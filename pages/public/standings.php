<?php
require_once __DIR__ . '/common.php';

// grab available seasons for the filter
$seasonsStmt = $db->prepare(
    'SELECT season_id, league, name
    FROM seasons
    ORDER BY name DESC, league'
);
$seasonsStmt->execute();
$seasons = $seasonsStmt->fetchAll();

// default to most recent season
$selectedSeason = filter_input(INPUT_GET, 'season_id', FILTER_VALIDATE_INT) ?: null;
if (!$selectedSeason && !empty($seasons)) {
    $selectedSeason = (int) $seasons[0]['season_id'];
}

// run standings query
$standings = [];
$seasonLabel = '';

if ($selectedSeason) {
    // find label for the selected season
    foreach ($seasons as $s) {
        if ((int) $s['season_id'] === $selectedSeason) {
            $seasonLabel = $s['league'] . ' · ' . $s['name'];
            break;
        }
    }


    // Using two different CTEs to aggregating standings for all teams within a season
    // Specifically, we care about the WINS / LOSS / TIE ratio and we order by the POINTS
    // Users will care about this report as it shows the global standings for any given season!

    $stmtStandings = $db->prepare(
        'WITH
teamMatches AS (
    # select home team id and home_core
    SELECT home_team_id AS team_id, home_score AS goals_for, away_score AS goals_against
    FROM matches WHERE season_id = ?
    # append the results to the bottom of the previous query
    # number of parameters needs to be the same
    UNION ALL
    SELECT away_team_id, away_score, home_score
    FROM matches WHERE season_id = ?
),
# this CTE references teamMatches,
# but sums up the results
teamStats AS (
    SELECT team_id,
           COUNT(*) AS games_played,
           SUM(goals_for > goals_against) AS wins,
           SUM(goals_for < goals_against) AS losses,
           SUM(goals_for = goals_against) AS ties,
           SUM(goals_for) AS goals_for,
           SUM(goals_against) AS goals_against
    FROM teamMatches
    GROUP BY team_id
)
# finally, select specified attributes from the last 
# CTE (teamStats)
SELECT t.team_id, t.name AS team_name,
       ts.games_played, ts.wins, ts.losses, ts.ties,
       (ts.wins * 2 + ts.ties) AS points,
       ts.goals_for, ts.goals_against
FROM teamStats ts
JOIN teams t ON t.team_id = ts.team_id
ORDER BY points DESC, wins DESC, goals_for DESC
'
    );

    $stmtStandings->execute([$selectedSeason, $selectedSeason]);
    $standings = $stmtStandings->fetchAll();
}

$pageTitle = 'Standings';
require BASE_PATH . '/views/public/standings.php';
