<?php
/*
 * STARTER SCREEN: Module 1, Login (owner: Khuzaimi)
 *
 * Works today: checks email + password against the database, safe database
 * question (prepared statement), password check on the scrambled password,
 * new session at login, generic error message, login and failed login logged.
 *
 * STILL TO BUILD (these are the marked controls for M1):
 *   - lock the account after too many wrong tries (users.failed_attempts / locked_until)
 *   - slow down repeated attempts from one place (rate limit)
 *   - registration page for sellers (role fixed to seller, password 10+ characters)
 *   - admin page to create rider accounts, block / unblock users
 *   - change password page
 *   - session timeout
 */
require __DIR__ . '/../app/bootstrap.php';

// A fake hash. It makes a wrong email take as long as a wrong password,
// so attackers cannot find out which emails exist.
const DUMMY_HASH = '$2y$10$ldZfqsJK86kPB3DwSXp03ehdXt.RrZCDjx3X/OSJnv/Tr9KrXbcu6';

if (current_user() !== null) {
    redirect(role_home(current_user()['role']));
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    $email    = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    $stmt = db()->prepare('SELECT id, full_name, password_hash, role, is_active FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    $passwordOk = password_verify($password, $user['password_hash'] ?? DUMMY_HASH);

    if ($user && (int) $user['is_active'] === 1 && $passwordOk) {
        login_user($user);
        audit_log('login_success', '', (int) $user['id']);
        redirect(role_home($user['role']));
    }

    audit_log('login_failed', 'email=' . substr($email, 0, 100)); // never the password
    $error = 'Invalid email or password.';
}

$pageTitle = 'Log in';
require __DIR__ . '/../app/layout/header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <div class="card p-4">
      <h1 class="h4 mb-3">Log in</h1>
      <?php if ($error !== ''): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
      <?php endif; ?>
      <form method="post" autocomplete="off">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label" for="email">Email</label>
          <input class="form-control" type="email" id="email" name="email" required maxlength="150">
        </div>
        <div class="mb-3">
          <label class="form-label" for="password">Password</label>
          <input class="form-control" type="password" id="password" name="password" required maxlength="200">
        </div>
        <button class="btn btn-primary w-100" type="submit">Log in</button>
      </form>
    </div>
  </div>
</div>
<?php require __DIR__ . '/../app/layout/footer.php'; ?>
