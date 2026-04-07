#!/usr/bin/env php
<?php

/**
 * Database Migration CLI
 * 
 * Usage:
 *   php migrate.php run      - Run pending migrations
 *   php migrate.php status   - Show migration status
 */

// Set the base path
define('BASE_PATH', __DIR__);

// Load environment variables
require_once BASE_PATH . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

// Load database configuration
require_once BASE_PATH . '/config/database.php';

// Load Migrator class
require_once BASE_PATH . '/src/Database/Migrator.php';

// Check command
$command = $argv[1] ?? 'help';

try {
    // Get database connection
    $dsn = "mysql:host=" . getenv('DB_HOST') . ";dbname=" . getenv('DB_NAME') . ";charset=utf8mb4";
    $pdo = new PDO(
        $dsn,
        getenv('DB_USER'),
        getenv('DB_PASS'),
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    $migrator = new Migrator($pdo, BASE_PATH . '/src/Database/migrations');

    switch ($command) {
        case 'run':
            $success = $migrator->run();
            exit($success ? 0 : 1);
            break;

        case 'status':
            $migrator->status();
            exit(0);
            break;

        case 'help':
        default:
            echo "Database Migration CLI\n\n";
            echo "Usage:\n";
            echo "  php migrate.php run    - Run pending migrations\n";
            echo "  php migrate.php status - Show migration status\n";
            echo "  php migrate.php help   - Show this help message\n";
            exit(0);
    }

} catch (PDOException $e) {
    echo "Database connection error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
