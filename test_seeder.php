<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

try {
    echo "Testing seeder directly...\n";
    
    $seeder = new Database\Seeders\TestSeeder;
    $seeder->run();
    
    echo "Seeder completed successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}