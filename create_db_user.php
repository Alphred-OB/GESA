<?php
echo "<h2>Create Database User Guide</h2>";

echo "<h3>Step 1: Login to cPanel</h3>";
echo "<ul>";
echo "<li>Go to yourdomain.com/cpanel</li>";
echo "<li>Login with your hosting credentials</li>";
echo "</ul>";

echo "<h3>Step 2: Create Database</h3>";
echo "<ul>";
echo "<li>Click 'MySQL Databases'</li>";
echo "<li>Create new database: <strong>kingsdev_gesa</strong></li>";
echo "</ul>";

echo "<h3>Step 3: Create User</h3>";
echo "<ul>";
echo "<li>Scroll down to 'MySQL Users'</li>";
echo "<li>Create user: <strong>kingsdev_gesa</strong></li>";
echo "<li>Set a password (write it down!)</li>";
echo "</ul>";

echo "<h3>Step 4: Add User to Database</h3>";
echo "<ul>";
echo "<li>Scroll down to 'Add User to Database'</li>";
echo "<li>Select user: kingsdev_gesa</li>";
echo "<li>Select database: kingsdev_gesa</li>";
echo "<li>Click 'Add'</li>";
echo "<li>Check 'ALL PRIVILEGES'</li>";
echo "<li>Click 'Make Changes'</li>";
echo "</ul>";

echo "<h3>Step 5: Update .env file</h3>";
echo "<pre>";
echo "DB_CONNECTION=mysql\n";
echo "DB_HOST=localhost\n";
echo "DB_PORT=3306\n";
echo "DB_DATABASE=kingsdev_gesa\n";
echo "DB_USERNAME=kingsdev_gesa\n";
echo "DB_PASSWORD=YOUR_PASSWORD_HERE\n";
echo "</pre>";

echo "<h3>Step 6: Clear Laravel Cache</h3>";
echo "<pre>";
echo "php artisan config:clear\n";
echo "php artisan cache:clear\n";
echo "php artisan migrate\n";
echo "</pre>";
?>
