<?php
declare(strict_types=1);

/* Small helpers used by every page. */

/** Escape text before printing it in HTML. Use on EVERY value you print. */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

/* Form protection: every POST form includes csrf_field() and the page calls csrf_verify(). */
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_verify(): void
{
    $sent = (string) ($_POST['csrf'] ?? '');
    $have = (string) ($_SESSION['csrf'] ?? '');
    if ($sent === '' || $have === '' || !hash_equals($have, $sent)) {
        show_error_page(400, 'Your form expired. Please go back and try again.');
    }
}

/* One-time messages shown after a redirect. */
function flash_set(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function flash_pull(): array
{
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}
