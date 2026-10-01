<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Buat direktori sementara di /tmp untuk Storage, Cache, dan Views
$tmpDirs = [
    '/tmp/storage/app',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// 2. Set Environment Variables
$_ENV['APP_STORAGE_PATH'] = '/tmp/storage';
$_ENV['LOG_CHANNEL'] = 'stderr';
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';

// 3. Buat database SQLite di /tmp
$dbPath = '/tmp/database.sqlite';
if (!file_exists($dbPath)) {
    if (file_exists(__DIR__ . '/../database/database.sqlite')) {
        copy(__DIR__ . '/../database/database.sqlite', $dbPath);
    } else {
        touch($dbPath);
    }
}

// 4. Load Autoload & Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->useStoragePath('/tmp/storage');
$app->useBootstrapPath('/tmp/bootstrap');

// 5. Paksa SCRIPT_NAME & PHP_SELF agar Router Laravel 11 membaca prefix /api secara tepat
if (isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['SCRIPT_NAME'] = '/index.php';
    $_SERVER['PHP_SELF'] = '/index.php';
}

// 6. Eksekusi Request
$request = Request::capture();
$response = $app->handle($request);
$response->send();