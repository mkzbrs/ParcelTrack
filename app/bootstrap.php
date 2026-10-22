<?php
declare(strict_types=1);

/* Every page starts with: require __DIR__ . '/../app/bootstrap.php';  (one more ../ in sub-folders) */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/errors.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/logger.php';
require_once __DIR__ . '/auth.php';

start_secure_session();
