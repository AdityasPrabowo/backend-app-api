<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Buat direktori sementara di /tmp
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

// 2. Set Environment
$_ENV['APP_STORAGE_PATH'] = '/tmp/storage';
$_ENV['LOG_CHANNEL'] = 'stderr';
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';

// 3. SQLite Database
$dbPath = '/tmp/database.sqlite';
if (!file_exists($dbPath)) {
    if (file_exists(__DIR__ . '/../database/database.sqlite')) {
        copy(__DIR__ . '/../database/database.sqlite', $dbPath);
    } else {
        touch($dbPath);
    }
}

// 4. Load Autoload & App
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->useStoragePath('/tmp/storage');
$app->useBootstrapPath('/tmp/bootstrap');

// --- DIAGNOSTICS LOGIC ---
// Jika URL diawali /api/, kita dump informasi URL-nya untuk cek pembacaan Vercel
$request = Request::capture();

// Tangkap path yang dibaca Laravel
$path = $request->path();
$uri = $_SERVER['REQUEST_URI'] ?? 'EMPTY';

// Jika mengakses /api/products, kembalikan JSON info routing
if (str_contains($uri, 'products')) {
    header('Content-Type: application/json');
    echo json_encode([
        'debug_mode' => true,
        'raw_request_uri' => $uri,
        'laravel_captured_path' => $path,
        'script_name' => $_SERVER['SCRIPT_NAME'] ?? '',
        'php_self' => $_SERVER['PHP_SELF'] ?? '',
    ], JSON_PRETTY_PRINT);
    exit;
}

$response = $app->handle($request);
$response->send();