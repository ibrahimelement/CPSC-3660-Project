<?php
require_once __DIR__ . '/../common.php';

$getId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$postId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

$id = $getId ?? $postId;

// verify no invalid id
if (!$id || $id < 1) {
    header('Location: ' . BASE_URL . '/pages/public/players.php');
    exit;
}

// grab the player
$db = get_db();
$stmt = $db->prepare('SELECT * FROM players WHERE player_id = ?');
$stmt->execute([$id]);
$player = $stmt->fetch();

// redirect if it doesn't exist
if (!$player) {
    set_flash('error', 'Player not found.');
    header('Location: ' . BASE_URL . '/pages/public/players.php');
    exit;
}

// variables to be populated conditionally
$errors = [];
$input = [
    'first_name' => $player['first_name'],
    'last_name' => $player['last_name'],
    'position' => $player['position'],
    'jersey_number' => $player['jersey_number'] ?? '',
    'level' => $player['level'] ?? '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // extract post request inputs from incoming request
    // or default to ''.
    $input['first_name'] = $_POST['first_name'] ?? '';
    $input['last_name'] = $_POST['last_name'] ?? '';
    $input['position'] = $_POST['position'] ?? '';
    $input['jersey_number'] = $_POST['jersey_number'] ?? '';
    $input['level'] = $_POST['level'] ?? '';

    // ensure fields are provided and not empty
    $firstName = validate_required('first_name', $input['first_name'], $errors);
    $lastName = validate_required('last_name', $input['last_name'], $errors);
    $position = validate_required('position', $input['position'], $errors);


    $allowedPositionsArr = allowed_positions();
    if ($position !== null && !in_array($position, $allowedPositionsArr, true)) {
        $errors['position'] = 'You selected a wrong position, please verify!';
        $position = null;
    }

    $jersey = null;
    $trimmedJersey = trim($input['jersey_number']);
    $hasTrimmedJersey = $trimmedJersey !== '';
    if ($hasTrimmedJersey) {
        $jersey = validate_int($input['jersey_number'], 0, 99);
        if ($jersey === null) {
            $errors['jersey_number'] = 'Jersy must be between 0 and 99, and not a decmial, please try again!';
        }
    }

    $level = str_or_null($input['level']);
    if ($level !== null && !in_array($level, allowed_levels(), true)) {
        $errors['level'] = 'You selected the wrong level!';
        $level = null;
    }

    // if no errors, then run UPDATE statement
    if (empty($errors)) {
        $stmt = $db->prepare(
            'UPDATE players
             SET first_name = ?, last_name = ?, position = ?,
                 jersey_number = ?, level = ?
             WHERE player_id = ?'
        );
        $stmt->execute([$firstName, $lastName, $position, $jersey, $level, $id]);

        set_flash('success', 'Player updated successfully.');

        // flash the new player id
        header('Location: ' . BASE_URL . '/pages/public/player_detail.php?id=' . $id);
        exit;
    }
}

$fullName = $player['first_name'] . ' ' . $player['last_name'];
$pageTitle = 'Edit Player';
require BASE_PATH . '/views/admin/players/edit.php';
