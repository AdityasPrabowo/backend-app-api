<?php

// Paksa PHP tampilkan semua error ke layar
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

try {
    // 1. Buat / pastikan file SQLite di /tmp Vercel
    $dbPath = '/tmp/database.sqlite';
    if (!file_exists($dbPath)) {
        if (file_exists(__DIR__ . '/../database/database.sqlite')) {
            copy(__DIR__ . '/../database/database.sqlite', $dbPath);
        } else {
            touch($dbPath);
        }
    }

    // 2. Load autoload & app
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    // 3. Eksekusi Request
    $request = Request::capture();
    $response = $app->handle($request);
    $response->send();

} catch (\Throwable $e) {
    // Jika crash fatal, cetak detail error-nya dalam bentuk JSON
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => explode("\n", $e->getTraceAsString())
    ], JSON_PRETTY_PRINT);
    exit;
}