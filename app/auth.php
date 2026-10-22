<?php
declare(strict_types=1);

/*
 * Login check and role check, used by EVERY page.
 * Owner: Khuzaimi (M1). Others only call the functions below.
 *
 * Every page that is not public must start with:
 *     require_login();                 // any logged-in user
 *     require_role('admin');           // or one or more specific roles
 *
 * Hiding a menu item is NOT security. The check here is what protects the page.
 */

function start_secure_session(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'httponly' => true,
            'samesite' => 'Lax',
            'secure'   => !empty($_SERVER['HTTPS']),
        ]);
        session_start();
    }
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

/** Call right after a successful password check. */
function login_user(array $user): void
{
    session_regenerate_id(true); // new session id at login
    $_SESSION['user'] = [
        'id'   => (int) $user['id'],
        'name' => (string) $user['full_name'],
        'role' => (string) $user['role'],
    ];
}

function logout_user(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

function role_home(string $role): string
{
    return match ($role) {
        'admin'  => '/admin/dashboard.php',
        'seller' => '/seller/parcel_new.php',
        default  => '/index.php',
    };
}

function require_login(): array
{
    $user = current_user();
    if ($user === null) {
        redirect('/login.php');
    }
    return $user;
}

function require_role(string ...$roles): array
{
    $user = require_login();
    if (!in_array($user['role'], $roles, true)) {
        audit_log('access_denied', 'role=' . $user['role'] . ' page=' . basename($_SERVER['SCRIPT_NAME'] ?? ''));
        show_error_page(403, 'You do not have access to this page.');
    }
    return $user;
}
