<?php
// Requires: BASE_URL defined, current_user() available (auth.php already included)
$page_title = $page_title ?? 'IceTrack';
$_user = current_user();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?> – IceTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-brand { font-weight: 700; letter-spacing: .04em; }
        .table th     { background-color: #f8f9fa; }
        body          { padding-bottom: 4rem; }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="<?= BASE_URL ?>/index.php">&#x1F3D2; IceTrack</a>
    <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#navMenu"
            aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="<?= BASE_URL ?>/pages/public/players.php">Players</a>
        </li>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if ($_user): ?>
          <?php if ($_user['role'] === 'admin'): ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="adminMenu"
                 data-bs-toggle="dropdown" aria-expanded="false">Admin</a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminMenu">
                <li><a class="dropdown-item" href="<?= BASE_URL ?>/pages/admin/players/create.php">Add Player</a></li>
              </ul>
            </li>
          <?php endif; ?>
          <li class="nav-item">
            <span class="nav-link text-secondary">
              <?= htmlspecialchars($_user['display_name'], ENT_QUOTES, 'UTF-8') ?>
            </span>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>/pages/auth/logout.php">Logout</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>/pages/auth/login.php">Login</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<main class="container">
