<?php
/*
 * STARTER SCREEN: Module 2, Add a parcel (owner: Tan Tong Chong)
 *
 * Works today: seller-only page, form checked on the server, safe database
 * questions, long random tracking number, first status saved to the history,
 * everything printed safely.
 *
 * STILL TO BUILD (these are the marked controls for M2):
 *   - "My parcels" page with search, showing ONLY this seller's parcels
 *   - parcel detail page with an owner check on the parcel id in the URL
 *   - slow down repeated lookups on the public tracking page (rate limit)
 *   - tighter checks (phone formats, address characters) and tests for each
 */
require __DIR__ . '/../../app/bootstrap.php';

$user = require_role('seller');

$old    = ['recipient_name' => '', 'recipient_phone' => '', 'recipient_address' => '', 'weight_kg' => ''];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    foreach ($old as $field => $_) {
        $old[$field] = trim((string) ($_POST[$field] ?? ''));
    }

    // --- checks on the server (the browser's checks can be skipped by attackers) ---
    $nameLen = mb_strlen($old['recipient_name']);
    if ($nameLen < 2 || $nameLen > 100) {
        $errors[] = 'Recipient name must be 2 to 100 characters.';
    }

    $phone = preg_replace('/[\s-]/', '', $old['recipient_phone']);
    if (!preg_match('/^\+?[0-9]{9,13}$/', $phone)) {
        $errors[] = 'Phone number must have 9 to 13 digits.';
    }

    $addrLen = mb_strlen($old['recipient_address']);
    if ($addrLen < 5 || $addrLen > 255) {
        $errors[] = 'Address must be 5 to 255 characters.';
    }

    if (!preg_match('/^\d{1,3}(\.\d{1,2})?$/', $old['weight_kg']) || (float) $old['weight_kg'] <= 0 || (float) $old['weight_kg'] > 100) {
        $errors[] = 'Weight must be more than 0 and at most 100 kg.';
    }

    if ($errors === []) {
        $pdo = db();
        $pdo->beginTransaction();

        $insert = $pdo->prepare(
            'INSERT INTO parcels (tracking_no, seller_id, recipient_name, recipient_phone, recipient_address, weight_kg)
             VALUES (?, ?, ?, ?, ?, ?)'
        );

        $trackingNo = null;
        for ($try = 0; $try < 5 && $trackingNo === null; $try++) {
            $candidate = 'PT' . strtoupper(bin2hex(random_bytes(8))); // long random number, cannot be guessed
            try {
                $insert->execute([$candidate, $user['id'], $old['recipient_name'], $phone, $old['recipient_address'], $old['weight_kg']]);
                $trackingNo = $candidate;
            } catch (PDOException $ex) {
                if ($ex->getCode() !== '23000') { // 23000 = number already used, try another
                    throw $ex;
                }
            }
        }

        if ($trackingNo === null) {
            $pdo->rollBack();
            $errors[] = 'Could not create the parcel. Please try again.';
        } else {
            $parcelId = (int) $pdo->lastInsertId();
            $pdo->prepare('INSERT INTO status_history (parcel_id, status, changed_by) VALUES (?, ?, ?)')
                ->execute([$parcelId, 'Registered', $user['id']]);
            $pdo->commit();

            audit_log('parcel_registered', 'tracking=' . $trackingNo);
            flash_set('success', 'Parcel registered. Tracking number: ' . $trackingNo);
            redirect('/seller/parcel_new.php');
        }
    }
}

$pageTitle = 'Add a parcel';
require __DIR__ . '/../../app/layout/header.php';
?>
<div class="row justify-content-center">
  <div class="col-lg-7">
    <div class="card p-4">
      <h1 class="h4 mb-3">Add a parcel</h1>
      <?php foreach ($errors as $msg): ?>
        <div class="alert alert-danger py-2"><?= e($msg) ?></div>
      <?php endforeach; ?>
      <form method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label" for="recipient_name">Recipient name</label>
          <input class="form-control" id="recipient_name" name="recipient_name" required maxlength="100" value="<?= e($old['recipient_name']) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label" for="recipient_phone">Recipient phone</label>
          <input class="form-control" id="recipient_phone" name="recipient_phone" required maxlength="20" value="<?= e($old['recipient_phone']) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label" for="recipient_address">Recipient address</label>
          <textarea class="form-control" id="recipient_address" name="recipient_address" rows="3" required maxlength="255"><?= e($old['recipient_address']) ?></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label" for="weight_kg">Weight (kg)</label>
          <input class="form-control" id="weight_kg" name="weight_kg" required maxlength="6" inputmode="decimal" value="<?= e($old['weight_kg']) ?>">
        </div>
        <button class="btn btn-primary" type="submit">Save parcel</button>
      </form>
    </div>
  </div>
</div>
<?php require __DIR__ . '/../../app/layout/footer.php'; ?>
