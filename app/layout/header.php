<?php
/* Shared page top. Set $pageTitle before including. */
$user = current_user();
$pageTitle = $pageTitle ?? 'ParcelTrack Lite';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?> · ParcelTrack Lite</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark pt-nav">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/index.php">ParcelTrack Lite</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-label="Menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="/track.php">Track a parcel</a></li>
        <?php if ($user && $user['role'] === 'seller'): ?>
          <li class="nav-item"><a class="nav-link" href="/seller/parcel_new.php">Add parcel</a></li>
        <?php endif; ?>
        <?php if ($user && $user['role'] === 'admin'): ?>
          <li class="nav-item"><a class="nav-link" href="/admin/dashboard.php">Dashboard</a></li>
        <?php endif; ?>
      </ul>
      <?php if ($user): ?>
        <form method="post" action="/logout.php" class="d-flex align-items-center gap-2">
          <?= csrf_field() ?>
          <span class="text-white-50 small"><?= e($user['name']) ?> (<?= e($user['role']) ?>)</span>
          <button class="btn btn-sm btn-outline-light" type="submit">Log out</button>
        </form>
      <?php else: ?>
        <a class="btn btn-sm btn-warning" href="/login.php">Log in</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
<main class="container py-4">
<?php foreach (flash_pull() as $f): ?>
  <div class="alert alert-<?= e($f['type']) ?>"><?= e($f['message']) ?></div>
<?php endforeach; ?>
