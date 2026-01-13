<?php

// Try to find the database credentials from .env if we can't read it
// But since I can't read .env, I'll try to use the ones from config/database.php defaults
// or better, I'll just use a PHP script that I can run via the web server if I could, 
// but I can't.

// Let's try to load Laravel properly but with a error handler.

try {
    require 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo "Connection successful!" . PHP_EOL;

    // 1. Find ANY student who has a balance that doesn't match a single due.
    // Specifically Year 3 students where balance is not 70 (and not 0).
    $students = App\Models\User::where('role', 'student')
        ->where('year', 3)
        ->get();

    echo "Checking " . $students->count() . " Year 3 students..." . PHP_EOL;

    foreach ($students as $student) {
        $activeDues = App\Models\Due::where('student_id', $student->user_id)
            ->where('is_active', true)
            ->whereIn('payment_status', ['owing', 'pending_verification'])
            ->get();

        if ($activeDues->sum('amount') > 70) {
            echo "Student ID: " . $student->user_id . " (" . ($student->fullname ?? $student->username) . ")" . PHP_EOL;
            echo "  Total Balance: GHS " . $activeDues->sum('amount') . PHP_EOL;
            echo "  Active Dues Records: " . $activeDues->count() . PHP_EOL;
            foreach ($activeDues as $due) {
                echo "    - ID: " . $due->due_id . " | Desc: '" . $due->description . "' | Year: " . $due->academic_year . " | Amount: " . $due->amount . " | Status: " . $due->payment_status . PHP_EOL;
            }
        }
    }

    // 2. Check for dues with nearly identical descriptions (e.g. whitespace)
    echo PHP_EOL . "Checking for dues with similar descriptions..." . PHP_EOL;
    $allDescriptions = App\Models\Due::where('is_active', true)
        ->select('description')
        ->distinct()
        ->pluck('description');

    foreach ($allDescriptions as $d1) {
        foreach ($allDescriptions as $d2) {
            if ($d1 !== $d2 && trim($d1) === trim($d2)) {
                echo "Conflict found: '" . $d1 . "' vs '" . $d2 . "'" . PHP_EOL;
            }
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}
