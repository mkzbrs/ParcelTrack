<?php
declare(strict_types=1);

/*
 * Safe error handling (policy P4).
 * Users only ever see a short, generic message.
 * The technical details go to storage/logs/error.log.
 */

ini_set('display_errors', '0');
error_reporting(E_ALL);

function log_error(string $message): void
{
    @file_put_contents(
        LOG_DIR . '/error.log',
        date('c') . ' ' . $message . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );
}

function show_error_page(int $code = 500, string $message = 'Something went wrong. Please try again later.'): never
{
    http_response_code($code);
    echo '<!doctype html><html lang="en"><head><meta charset="utf-8">'
       . '<meta name="viewport" content="width=device-width, initial-scale=1">'
       . '<title>ParcelTrack Lite</title></head><body style="font-family:sans-serif;padding:2rem">'
       . '<h1>Sorry</h1><p>' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p>'
       . '<p><a href="/">Back to home</a></p></body></html>';
    exit;
}

set_error_handler(function (int $no, string $text, string $file, int $line): bool {
    log_error("PHP error [$no] $text in $file:$line");
    return true;
});

set_exception_handler(function (Throwable $e): void {
    log_error(get_class($e) . ': ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    show_error_page(500);
});
