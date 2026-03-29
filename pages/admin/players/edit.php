<?php
declare(strict_types=1);

// Milestone update page — edit an existing player record.

require_once __DIR__ . '/../../../config/db.php';
require_once BASE_PATH . '/lib/auth.php';
require_once BASE_PATH . '/lib/flash.php';
require_once BASE_PATH . '/lib/validators.php';

_session_start();
require_admin();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)
  ?? filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header('Location: ' . BASE_URL . '/pages/public/players.php');
    exit;
}

$db   = get_db();
$stmt = $db->prepare('SELECT * FROM players WHERE player_id = ?');
$stmt->execute([$id]);
$player = $stmt->fetch();

if (!$player) {
    set_flash('error', 'Player not found.');
    header('Location: ' . BASE_URL . '/pages/public/players.php');
    exit;
}

$errors = [];
$input  = [
    'first_name'    => $player['first_name'],
    'last_name'     => $player['last_name'],
    'position'      => $player['position'],
    'jersey_number' => $player['jersey_number'] ?? '',
    'level'         => $player['level'] ?? '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input['first_name']    = $_POST['first_name']    ?? '';
    $input['last_name']     = $_POST['last_name']     ?? '';
    $input['position']      = $_POST['position']      ?? '';
    $input['jersey_number'] = $_POST['jersey_number'] ?? '';
    $input['level']         = $_POST['level']         ?? '';

    $first_name = validate_required('first_name', $input['first_name'], $errors);
    $last_name  = validate_required('last_name',  $input['last_name'],  $errors);
    $position   = validate_required('position',   $input['position'],   $errors);

    if ($position !== null && !in_array($position, allowed_positions(), true)) {
        $errors['position'] = 'Invalid position selected.';
        $position = null;
    }

    $jersey = null;
    if (trim($input['jersey_number']) !== '') {
        $jersey = validate_int($input['jersey_number'], 0, 99);
        if ($jersey === null) {
            $errors['jersey_number'] = 'Jersey number must be a whole number between 0 and 99.';
        }
    }

    $level = str_or_null($input['level']);
    if ($level !== null && !in_array($level, allowed_levels(), true)) {
        $errors['level'] = 'Invalid level selected.';
        $level = null;
    }

    if (empty($errors)) {
        $stmt = $db->prepare(
            'UPDATE players
             SET first_name = ?, last_name = ?, position = ?,
                 jersey_number = ?, level = ?
             WHERE player_id = ?'
        );
        $stmt->execute([$first_name, $last_name, $position, $jersey, $level, $id]);

        set_flash('success', 'Player updated successfully.');
        header('Location: ' . BASE_URL . '/pages/public/players.php');
        exit;
    }
}

$full_name  = $player['first_name'] . ' ' . $player['last_name'];
$page_title = 'Edit Player';
require BASE_PATH . '/views/admin/players/edit.php';
