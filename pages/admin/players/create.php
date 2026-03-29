<?php
declare(strict_types=1);

// Milestone insert page — create a new player record.

require_once __DIR__ . '/../../../config/db.php';
require_once BASE_PATH . '/lib/auth.php';
require_once BASE_PATH . '/lib/flash.php';
require_once BASE_PATH . '/lib/validators.php';

_session_start();
require_admin();

$errors = [];
$input  = [
    'first_name'    => '',
    'last_name'     => '',
    'position'      => '',
    'jersey_number' => '',
    'level'         => '',
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
        $db   = get_db();
        $stmt = $db->prepare(
            'INSERT INTO players (first_name, last_name, position, jersey_number, level)
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$first_name, $last_name, $position, $jersey, $level]);

        set_flash('success', 'Player "' . htmlspecialchars($first_name . ' ' . $last_name, ENT_QUOTES, 'UTF-8') . '" created successfully.');
        header('Location: ' . BASE_URL . '/pages/public/players.php');
        exit;
    }
}

$page_title = 'Add Player';
require BASE_PATH . '/views/admin/players/create.php';
