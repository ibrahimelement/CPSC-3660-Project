<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';
require_once BASE_PATH . '/lib/auth.php';
require_once BASE_PATH . '/lib/flash.php';

_session_start();

$db     = get_db();
$search = trim($_GET['q'] ?? '');

if ($search !== '') {
    $like = '%' . $search . '%';
    $stmt = $db->prepare(
        'SELECT p.player_id, p.first_name, p.last_name, p.position,
                p.jersey_number, p.level,
                t.name AS team_name
         FROM players p
         LEFT JOIN player_team_memberships m
                ON m.player_id = p.player_id AND m.end_date IS NULL
         LEFT JOIN teams t ON t.team_id = m.team_id
         WHERE p.first_name LIKE ? OR p.last_name LIKE ? OR p.position LIKE ?
         ORDER BY p.last_name, p.first_name'
    );
    $stmt->execute([$like, $like, $like]);
} else {
    $stmt = $db->prepare(
        'SELECT p.player_id, p.first_name, p.last_name, p.position,
                p.jersey_number, p.level,
                t.name AS team_name
         FROM players p
         LEFT JOIN player_team_memberships m
                ON m.player_id = p.player_id AND m.end_date IS NULL
         LEFT JOIN teams t ON t.team_id = m.team_id
         ORDER BY p.last_name, p.first_name'
    );
    $stmt->execute();
}

$players    = $stmt->fetchAll();
$pageTitle = 'Players';
require BASE_PATH . '/views/public/players.php';
