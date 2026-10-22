<?php
require __DIR__ . '/../app/bootstrap.php';

$user = current_user();
if ($user === null) {
    redirect('/login.php');
}

$pageTitle = 'Home';
require __DIR__ . '/../app/layout/header.php';
?>
<div class="card p-4">
  <h1 class="h4">Welcome, <?= e($user['name']) ?></h1>
  <p class="mb-3">You are signed in as <strong><?= e($user['role']) ?></strong>.</p>
  <?php if ($user['role'] === 'seller'): ?>
    <a class="btn btn-primary" href="/seller/parcel_new.php">Add a parcel</a>
  <?php elseif ($user['role'] === 'admin'): ?>
    <a class="btn btn-primary" href="/admin/dashboard.php">Open the dashboard</a>
  <?php else: ?>
    <p class="text-muted mb-0">Rider pages are not built yet (Module 3, next step).</p>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/../app/layout/footer.php'; ?>
