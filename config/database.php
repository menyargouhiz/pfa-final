<?php

// ---------------------------------------------------------------------------
// Load environment variables from config/.env
// (No external library needed — plain key=value parsing)
// ---------------------------------------------------------------------------
$envFile = __DIR__ . '/.env';

if (!file_exists($envFile)) {
    error_log('Missing config/.env file');
    http_response_code(500);
    die(json_encode(['error' => 'Server configuration error']));
}

$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    // Skip comments
    if (str_starts_with(trim($line), '#')) continue;

    if (str_contains($line, '=')) {
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

// ---------------------------------------------------------------------------
// Session (must run before any output)
// ---------------------------------------------------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 86400,
        'path'     => '/',
        'domain'   => '',
        'secure'   => ($_ENV['APP_ENV'] ?? 'development') === 'production',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// ---------------------------------------------------------------------------
// Database connection
// ---------------------------------------------------------------------------
if (!defined('PHPUNIT_RUNNING')) {
    try {
        $cnx = new PDO(
            "mysql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']};charset=utf8mb4",
            $_ENV['DB_USER'],
            $_ENV['DB_PASS']
        );
        $cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $cnx->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Log the real error internally — never expose it to the client
        error_log('Database connection failed: ' . $e->getMessage());
        http_response_code(500);
        die(json_encode(['error' => 'Database unavailable']));
    }
}