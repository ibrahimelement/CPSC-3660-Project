<?php
declare(strict_types=1);

// import libraries
require_once __DIR__ . '/../config/db.php';
require_once BASE_PATH . '/lib/auth.php';
require_once BASE_PATH . '/lib/flash.php';

// create user session
_session_start();

$db = get_db();
