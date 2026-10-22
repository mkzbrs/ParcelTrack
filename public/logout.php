<?php
/* Module 1 (Khuzaimi): log out. The session is destroyed on the server. */
require __DIR__ . '/../app/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    if (current_user() !== null) {
        audit_log('logout');
    }
    logout_user();
}
redirect('/login.php');
