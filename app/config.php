<?php
declare(strict_types=1);

/*
 * Settings come from a .env file in the project root (outside public/)
 * or from real environment variables. NEVER write passwords or keys in
 * this file (policy P4). .env is ignored by Git.
 */

define('BASE_PATH', dirname(__DIR__));
define('LOG_DIR', BASE_PATH . '/storage/logs');
define('UPLOAD_DIR', BASE_PATH . '/storage/uploads');

function load_env(string $file): void
{
    if (!is_readable($file)) {
        return;
    }
    foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        if (getenv($key) === false) {
            putenv($key . '=' . trim($value));
        }
    }
}

function env(string $key, string $default = ''): string
{
    $value = getenv($key);
    return $value === false ? $default : $value;
}

load_env(BASE_PATH . '/.env');
