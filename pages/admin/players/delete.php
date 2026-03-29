<?php
declare(strict_types=1);

// import libraries
require_once __DIR__ . '/../../../config/db.php';
require_once BASE_PATH . '/lib/auth.php';
require_once BASE_PATH . '/lib/flash.php';

// ensure session is created and admin only
_session_start();
require_admin();

$getId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$postId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

$id = $getId ?? $postId;

// no id, or invalid id
if (!$id || $id < 1) {
    // redirect to another page
    header('Location: ' . BASE_URL . '/pages/public/players.php');
    exit;
}

// grab specific player information
$db = get_db();
$stmt = $db->prepare('SELECT * FROM players WHERE player_id = ?');
$stmt->execute([$id]);
$player = $stmt->fetch();

if (!$player) {
    set_flash('error', 'No play found (invalid player), please verify ID.');
    // redirect
    header('Location: ' . BASE_URL . '/pages/public/players.php');
    exit;
}

$fkError = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        // delete the player
        $stmt = $db->prepare('DELETE FROM players WHERE player_id = ?');
        $stmt->execute([$id]);

        // if we get here, then nothing was thrown
        set_flash('success', 'Player has been deleted.');

        // redirect
        header('Location: ' . BASE_URL . '/pages/public/players.php');

        exit;
    } catch (PDOException $e) {
        // check if we get a code 23000 (FK constraint issue)
        if ($e->getCode() === '23000') {
            $fkError = 'Cannot delete this player due to foreign key issues, existing statistics or references (illegal delete).';
        } else {
            $fkError = 'A database error occurred. Please try again.';
        }
    }
}

$fullName = $player['first_name'] . ' ' . $player['last_name'];
$pageTitle = 'Delete Player';
require BASE_PATH . '/views/admin/players/delete.php';
