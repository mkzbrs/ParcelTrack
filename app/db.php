<?php
declare(strict_types=1);

/*
 * One shared database connection.
 * Rule for everyone: every query that uses user input MUST use
 * db()->prepare(...) with ? placeholders. Never join input into the SQL text.
 */

function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = env('DB_DSN');
        if ($dsn === '') {
            $dsn = 'mysql:host=' . env('DB_HOST', '127.0.0.1')
                 . ';dbname=' . env('DB_NAME', 'parceltrack')
                 . ';charset=utf8mb4';
        }
        $pdo = new PDO($dsn, env('DB_USER'), env('DB_PASS'), [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }

    return $pdo;
}
