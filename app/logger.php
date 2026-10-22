<?php
declare(strict_types=1);

/*
 * Activity log (policy P5): logins, failed logins, status changes.
 * NEVER put passwords or full addresses in $detail.
 * Logging must never break the page, so errors are written to the error log only.
 */

function audit_log(string $event, string $detail = '', ?int $userId = null): void
{
    try {
        if ($userId === null && isset($_SESSION['user']['id'])) {
            $userId = (int) $_SESSION['user']['id'];
        }
        $stmt = db()->prepare(
            'INSERT INTO audit_log (user_id, event_type, detail, ip) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([
            $userId,
            substr($event, 0, 50),
            substr($detail, 0, 255),
            substr($_SERVER['REMOTE_ADDR'] ?? '', 0, 45),
        ]);
    } catch (Throwable $e) {
        log_error('audit_log failed: ' . $e->getMessage());
    }
}
