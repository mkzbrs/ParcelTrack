<?php
/*
 * STARTER SCREEN: Module 2, Public tracking (owner: Tan Tong Chong)
 *
 * Anyone can use this page without logging in.
 * It shows ONLY status, date and time. It never reads or shows the
 * recipient's name, phone or address.
 *
 * STILL TO BUILD: slow down repeated lookups from the same visitor (rate limit).
 */
require __DIR__ . '/../app/bootstrap.php';

$number   = strtoupper(trim((string) ($_GET['no'] ?? '')));
$searched = $number !== '';
$rows     = [];

if ($searched && preg_match('/^PT[0-9A-F]{16}$/', $number)) {
    $stmt = db()->prepare(
        'SELECT h.status, h.changed_at
           FROM status_history h
           JOIN parcels p ON p.id = h.parcel_id
          WHERE p.tracking_no = ?
          ORDER BY h.changed_at, h.id'
    );
    $stmt->execute([$number]);
    $rows = $stmt->fetchAll();
}

$pageTitle = 'Track a parcel';
require __DIR__ . '/../app/layout/header.php';
?>
<div class="row justify-content-center">
  <div class="col-lg-7">
    <div class="card p-4">
      <h1 class="h4 mb-3">Track a parcel</h1>
      <form method="get" class="d-flex gap-2 mb-3">
        <input class="form-control" name="no" maxlength="18" placeholder="Tracking number, for example PT0123456789ABCDEF" value="<?= e($number) ?>" required>
        <button class="btn btn-primary" type="submit">Track</button>
      </form>

      <?php if ($searched && $rows === []): ?>
        <div class="alert alert-warning mb-0">We could not find a parcel with that number.</div>
      <?php elseif ($rows !== []): ?>
        <table class="table mb-0">
          <thead><tr><th>Status</th><th>Date</th><th>Time</th></tr></thead>
          <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><span class="badge pt-badge-<?= e(str_replace(' ', '-', $r['status'])) ?>"><?= e($r['status']) ?></span></td>
              <td><?= e(date('d M Y', strtotime($r['changed_at']))) ?></td>
              <td><?= e(date('H:i', strtotime($r['changed_at']))) ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php require __DIR__ . '/../app/layout/footer.php'; ?>
