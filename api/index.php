<?php

// Salip pembuatan file SQLite di /tmp agar read-write Vercel aman
$dbPath = '/tmp/database.sqlite';
if (!file_exists($dbPath)) {
    if (file_exists(__DIR__ . '/../database/database.sqlite')) {
        copy(__DIR__ . '/../database/database.sqlite', $dbPath);
    } else {
        touch($dbPath);
    }
}

// Teruskan ke entry point Laravel
require __DIR__ . '/../public/index.php';