<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';
require_once BASE_PATH . '/lib/auth.php';
require_once BASE_PATH . '/lib/flash.php';

_session_start();

// Redirect already-logged-in users
if (is_logged_in()) {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

$errors = [];
$email  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password =      $_POST['password'] ?? '';

    if ($email === '') {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format.';
    }

    if ($password === '') {
        $errors['password'] = 'Password is required.';
    }

    if (empty($errors)) {
        $db   = get_db();
        $stmt = $db->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([strtolower($email)]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            login_user($user);
            // Basic open-redirect guard: only allow same-origin relative paths
            $next = $_POST['next'] ?? '';
            if ($next !== '' && str_starts_with($next, '/') && !str_starts_with($next, '//')) {
                header('Location: ' . $next);
            } else {
                header('Location: ' . BASE_URL . '/index.php');
            }
            exit;
        }

        $errors['form'] = 'Invalid email or password.';
    }
}

$next_hidden = htmlspecialchars($_GET['next'] ?? '', ENT_QUOTES, 'UTF-8');
$page_title  = 'Login';
require BASE_PATH . '/views/auth/login.php';
