<?php
require_once __DIR__ . '/../common.php';

// if user is already logged in, redirect them
if (is_logged_in()) {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

$errors = [];
$email = '';

// on submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '') {
        $errors['email'] = 'No email provided';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email is invalid';
    }

    if ($password === '') {
        $errors['password'] = 'No password provided';
    }

    if (empty($errors)) {

        // run query to grab user first
        $db = get_db();
        $stmt = $db->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([strtolower($email)]);
        $user = $stmt->fetch();

        // check password hash
        if ($user && password_verify($password, $user['password_hash'])) {

            // login user and redirect
            login_user($user);
            $next = $_POST['next'] ?? '';
            if ($next !== '' && str_starts_with($next, '/') && !str_starts_with($next, '//')) {
                header('Location: ' . $next);
            } else {
                header('Location: ' . BASE_URL . '/index.php');
            }
            exit;
        }

        $errors['form'] = 'Wrong username or password!';
    }
}

$nextHidden = htmlspecialchars($_GET['next'] ?? '', ENT_QUOTES, 'UTF-8');
$pageTitle = 'Login';
require BASE_PATH . '/views/auth/login.php';
