<?php
/*
 * STARTER SCREEN: Module 3, Admin dashboard (owner: Adam)
 *
 * Works today: admin-only page (checked on the server), counts of parcels by status.
 *
 * STILL TO BUILD (these are the marked controls for M3):
 *   - admin page to give a parcel to a rider (sets rider_id and status Assigned)
 *   - rider page: list ONLY the parcels assigned to that rider
 *   - rider status update with a server check that the parcel is theirs,
 *     only allowed statuses, and every change saved to status_history + audit log
 *   - one photo upload (JPG/PNG): check type, size and real contents, rename,
 *     save in storage/uploads (outside public/)
 */
require __DIR__ . '/../../app/bootstrap.php';

$user = require_role('admin');

$statuses = ['Registered', 'Assigned', 'Out for Delivery', 'Delivered', 'Failed'];
$counts   = array_fill_keys($statuses, 0);

// No user input in this query, so a plain query is fine here.
foreach (db()->query('SELECT current_status, COUNT(*) AS total FROM parcels GROUP BY current_status') as $row) {
    $counts[$row['current_status']] = (int) $row['total'];
}
$total = array_sum($counts);

$pageTitle = 'Dashboard';
require __DIR__ . '/../../app/layout/header.php';
?>
<h1 class="h4 mb-3">Parcels by status <span class="text-muted fs-6">(<?= e((string) $total) ?> in total)</span></h1>
<div class="row g-3">
  <?php foreach ($statuses as $status): ?>
    <div class="col-6 col-md-4 col-lg">
      <div class="card p-3 h-100">
        <span class="badge pt-badge-<?= e(str_replace(' ', '-', $status)) ?> align-self-start mb-2"><?= e($status) ?></span>
        <div class="pt-stat"><?= e((string) $counts[$status]) ?></div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<?php require __DIR__ . '/../../app/layout/footer.php'; ?>
