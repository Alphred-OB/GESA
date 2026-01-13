<?php

// Find DB config
$databaseConfig = file_get_contents('config/database.php');
// We need to find the default connection and then its details.
// Since we can't easily parse this without a full PHP env, let's try a different way.

// Usually in XAMPP it's root/no password.
$host = '127.0.0.1';
$user = 'root';
$pass = ''; // Default XAMPP
$db = 'laravel'; // Guessing from common names, but wait!

// Let's try to find the DB name from the error I got earlier.
// The error showed "mysql:host=127.0.0.1;port=3306;dbname=gesa"? 
// No, it was cut off.

// Let's use Laravel again but just query the DB directly via PDO to be faster.

try {
    require 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    $results = Illuminate\Support\Facades\DB::select("
        SELECT academic_year, description, COUNT(*) as count, SUM(is_active) as active_count
        FROM dues
        GROUP BY academic_year, description
        ORDER BY academic_year DESC
    ");

    echo "--- DUES TYPES SUMMARY ---" . PHP_EOL;
    foreach ($results as $row) {
        echo "Year: {$row->academic_year} | Desc: '{$row->description}' | Total: {$row->count} | Active: {$row->active_count}" . PHP_EOL;
    }

    echo PHP_EOL . "--- SEARCHING FOR STUDENTS WITH > 1 ACTIVE DUE FOR SAME YEAR ---" . PHP_EOL;
    $dups = Illuminate\Support\Facades\DB::select("
        SELECT student_id, academic_year, COUNT(*) as c, SUM(amount) as total
        FROM dues
        WHERE is_active = 1
        GROUP BY student_id, academic_year
        HAVING c > 1
        LIMIT 10
    ");

    foreach ($dups as $row) {
        $student = Illuminate\Support\Facades\DB::table('users')->where('user_id', $row->student_id)->first();
        echo "Student: " . ($student->fullname ?? $student->username) . " (ID: {$row->student_id}) - Year: {$row->academic_year}" . PHP_EOL;
        echo "  Dues Count: {$row->c} | Total Amount: GHS {$row->total}" . PHP_EOL;
        
        $details = Illuminate\Support\Facades\DB::table('dues')
            ->where('student_id', $row->student_id)
            ->where('academic_year', $row->academic_year)
            ->where('is_active', 1)
            ->get();
        foreach ($details as $d) {
             echo "    - ID: {$d->due_id} | Desc: '{$d->description}' | Amount: {$d->amount}" . PHP_EOL;
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
