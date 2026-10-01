<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Buat database sqlite kosong di /tmp Vercel jika belum ada
$dbPath = '/tmp/database.sqlite';
if (!file_exists($dbPath)) {
    if (file_exists(__DIR__ . '/../database/database.sqlite')) {
        copy(__DIR__ . '/../database/database.sqlite', $dbPath);
    } else {
        touch($dbPath);
    }
}

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$request = Request::capture();
$response = $app->handle($request);
$response->send();