<?php
declare(strict_types=1);

// import library methods from /lib
require_once __DIR__ . '/../../../config/db.php';
require_once BASE_PATH . '/lib/auth.php';
require_once BASE_PATH . '/lib/flash.php';
require_once BASE_PATH . '/lib/validators.php';

// ensure session is created on this page
_session_start();

// ensure user is an admin, of exit early
require_admin();

$errors = [];

// form input parameters
$input = [
    'first_name' => '',
    'last_name' => '',
    'position' => '',
    'jersey_number' => '',
    'level' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // extract and populate the $input object from the incoming request
    $input['first_name'] = $_POST['first_name'] ?? '';
    $input['last_name'] = $_POST['last_name'] ?? '';
    $input['position'] = $_POST['position'] ?? '';
    $input['jersey_number'] = $_POST['jersey_number'] ?? '';
    $input['level'] = $_POST['level'] ?? '';

    // ensure that these fields are provided, or add a new error
    $firstName = validate_required('first_name', $input['first_name'], $errors);
    $lastName = validate_required('last_name', $input['last_name'], $errors);
    $position = validate_required('position', $input['position'], $errors);

    $allowedPositionsArr = allowed_positions();
    if ($position !== null && !in_array($position, $allowedPositionsArr, true)) {
        $errors['position'] = 'You selected a wrong position';
        $position = null;
    }

    $jersey = null;
    $trimmedJersey = trim($input['jersey_number']);
    $jerseyProvided = $trimmedJersey !== '';

    if ($jerseyProvided) {
        $jersey = validate_int($input['jersey_number'], 0, 99);
        if ($jersey === null) {
            $errors['jersey_number'] = 'Jersey must be between 0 and 99 (no decimals)';
        }
    }

    $level = str_or_null($input['level']);
    $allowedLevelsArr = allowed_levels();

    if ($level !== null && !in_array($level, $allowedLevelsArr, true)) {
        $errors['level'] = 'Wrong level selected!';
        $level = null;
    }

    if (empty($errors)) {
        $db = get_db();

        $stmt = $db->prepare(
            'INSERT INTO players (level, position, jersey_number, first_name, last_name)
            VALUES (?, ?, ?, ?, ?)'
        );

        $stmt->execute([$level, $position, $jersey, $firstName, $lastName]);

        set_flash('success', 'Player "' . htmlspecialchars($firstName . ' ' . $lastName, ENT_QUOTES, 'UTF-8') . '" created successfully!');

        header('Location: ' . BASE_URL . '/pages/public/players.php');
        exit;
    }
}

$pageTitle = 'Add Player';
require BASE_PATH . '/views/admin/players/create.php';
