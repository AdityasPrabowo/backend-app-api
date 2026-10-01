<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Tentukan path SQLite di folder sementara Vercel
$dbPath = '/tmp/database.sqlite';
$isNew = !file_exists($dbPath);

if ($isNew) {
    if (file_exists(__DIR__ . '/../database/database.sqlite')) {
        copy(__DIR__ . '/../database/database.sqlite', $dbPath);
    } else {
        touch($dbPath);
    }
}

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Otomatis jalankan migration jika DB baru dibuat
if ($isNew) {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate --force');
    } catch (\Throwable $e) {
        // Abaikan error jika migration sudah ada
    }
}

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);