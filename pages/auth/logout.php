<?php
require_once __DIR__ . '/../common.php';

// wipe out user session cookies
logout_user();
set_flash('success', 'You have been logged out.');
header('Location: ' . BASE_URL . '/index.php');
exit;
