<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Buat direktori sementara di /tmp untuk Storage, Cache, dan Logs Laravel
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

// 2. Set Environment Variables wajib untuk Read-Only Vercel
$_ENV['APP_STORAGE_PATH'] = '/tmp/storage';
$_ENV['LOG_CHANNEL'] = 'stderr';
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';

// 3. Buat file SQLite di /tmp
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

// Timpa path storage & bootstrap cache bawaan ke /tmp
$app->useStoragePath('/tmp/storage');
$app->useBootstrapPath('/tmp/bootstrap');

// 5. Eksekusi Request
$request = Request::capture();
$response = $app->handle($request);
$response->send();