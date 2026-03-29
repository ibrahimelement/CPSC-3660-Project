<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';
require_once BASE_PATH . '/lib/auth.php';
require_once BASE_PATH . '/lib/flash.php';

logout_user();
set_flash('success', 'You have been logged out.');
header('Location: ' . BASE_URL . '/index.php');
exit;
