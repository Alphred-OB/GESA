<?php
require_once 'vendor/autoload.php';

try {
    // Load Laravel environment
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "Testing database connection...\n";
    
    // Test connection
    $pdo = DB::connection()->getPdo();
    echo "✅ SUCCESS: Database connected!\n";
    echo "Database: " . DB::connection()->getDatabaseName() . "\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "\nPossible solutions:\n";
    echo "1. Check if MySQL/XAMPP is running\n";
    echo "2. Verify database credentials in .env\n";
    echo "3. Create the database if it doesn't exist\n";
}
?>
