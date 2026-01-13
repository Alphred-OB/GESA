<?php
// Database Connection Test Script
echo "<h2>Database Connection Test</h2>";

// Test common database hosts
$hosts = [
    'localhost',
    '127.0.0.1',
    'mysql',
    'mariadb',
    '127.0.0.1:3306',
    'localhost:3306'
];

$database = 'kingsdev_gesa';
$username = 'kingsdev_gesa';
$password = ''; // Add your password if you know it

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Host</th><th>Status</th><th>Error</th></tr>";

foreach ($hosts as $host) {
    echo "<tr><td>$host</td>";
    
    try {
        $dsn = "mysql:host=$host;dbname=$database;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        $pdo = new PDO($dsn, $username, $password, $options);
        echo "<td style='color: green;'>✓ Connected</td>";
        echo "<td>Success!</td>";
        
        // Test a simple query
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $result = $stmt->fetch();
        echo "<td>Users count: {$result['count']}</td>";
        
    } catch (PDOException $e) {
        echo "<td style='color: red;'>✗ Failed</td>";
        echo "<td>" . htmlspecialchars($e->getMessage()) . "</td>";
    }
    
    echo "</tr>";
}

echo "</table>";

echo "<h3>Next Steps:</h3>";
echo "<ol>";
echo "<li>Find which host shows '✓ Connected'</li>";
echo "<li>Use that host in your .env file</li>";
echo "<li>If none work, contact your hosting provider</li>";
echo "</ol>";

echo "<h3>Current .env settings:</h3>";
echo "<pre>";
echo "DB_CONNECTION=mysql\n";
echo "DB_HOST=??? (use the working one from above)\n";
echo "DB_PORT=3306\n";
echo "DB_DATABASE=kingsdev_gesa\n";
echo "DB_USERNAME=kingsdev_gesa\n";
echo "DB_PASSWORD=your_password_here\n";
echo "</pre>";
?>
