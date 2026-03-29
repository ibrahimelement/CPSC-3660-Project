<?php
declare(strict_types=1);

// Milestone delete page — safely delete a player with FK-safety check.

require_once __DIR__ . '/../../../config/db.php';
require_once BASE_PATH . '/lib/auth.php';
require_once BASE_PATH . '/lib/flash.php';

_session_start();
require_admin();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)
    ?? filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header('Location: ' . BASE_URL . '/pages/public/players.php');
    exit;
}

$db = get_db();
$stmt = $db->prepare('SELECT * FROM players WHERE player_id = ?');
$stmt->execute([$id]);
$player = $stmt->fetch();

if (!$player) {
    set_flash('error', 'Player not found.');
    header('Location: ' . BASE_URL . '/pages/public/players.php');
    exit;
}

$fk_error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Confirm the POST id matches the URL id (CSRF-style sanity check)
    $post_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if ((int) $post_id !== (int) $id) {
        set_flash('error', 'Invalid request.');
        header('Location: ' . BASE_URL . '/pages/public/players.php');
        exit;
    }

    try {
        $stmt = $db->prepare('DELETE FROM players WHERE player_id = ?');
        $stmt->execute([$id]);
        set_flash('success', 'Player "' . htmlspecialchars(
            $player['first_name'] . ' ' . $player['last_name'],
            ENT_QUOTES,
            'UTF-8'
        ) . '" has been deleted.');
        header('Location: ' . BASE_URL . '/pages/public/players.php');
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            $fk_error = 'Cannot delete this player because they have match statistics or '
                . 'team membership records on file. Remove those records first.';
        } else {
            $fk_error = 'A database error occurred. Please try again.';
        }
    }
}

$full_name = $player['first_name'] . ' ' . $player['last_name'];
$page_title = 'Delete Player';
require BASE_PATH . '/views/admin/players/delete.php';
