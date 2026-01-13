<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Find Year 3 students with high outstanding balances
$students = App\Models\User::where('role', 'student')
    ->where('year', 3)
    ->where('is_graduated', false)
    ->whereNotNull('email_verified_at')
    ->with(['dues' => function($q) { $q->where('payment_status', 'owing')->where('is_active', true); }])
    ->get()
    ->filter(function($s) { return $s->dues->sum('amount') >= 140; })
    ->take(5);

echo "=== Year 3 Students with balance >= GHS 140 ===" . PHP_EOL;

foreach($students as $student) {
    echo PHP_EOL . 'Student ' . $student->user_id . ' (' . ($student->fullname ?? $student->username) . ') - Class: ' . $student->class . ', Year: ' . $student->year . PHP_EOL;
    echo '  Total Outstanding: GHS ' . $student->dues->sum('amount') . PHP_EOL;
    echo '  Dues count: ' . $student->dues->count() . PHP_EOL;
    foreach($student->dues as $due) {
        echo '  - ID#' . $due->due_id . ': ' . $due->description . ' (' . $due->academic_year . '): GHS ' . $due->amount . PHP_EOL;
    }
}

echo PHP_EOL . "=== Checking dues amounts for Year 3 students ===" . PHP_EOL;

// Check if any Year 3 student has dues with amount > 70
$wrongAmountDues = App\Models\Due::whereHas('student', function($q) {
    $q->where('year', 3);
})->where('payment_status', 'owing')
  ->where('is_active', true)
  ->where('amount', '>', 70)
  ->with('student:user_id,fullname,class,year')
  ->limit(10)
  ->get();

echo "Dues with amount > GHS 70 for Year 3 students: " . $wrongAmountDues->count() . PHP_EOL;
foreach($wrongAmountDues as $due) {
    echo '  - ID#' . $due->due_id . ' - Student: ' . ($due->student->fullname ?? 'Unknown') . PHP_EOL;
    echo '    Desc: ' . $due->description . ' (' . $due->academic_year . '): GHS ' . $due->amount . PHP_EOL;
}

echo PHP_EOL . "=== Check DefaultDueConfig for Year 3 ===" . PHP_EOL;
$configs = App\Models\DefaultDueConfig::where('year', '3')->where('is_active', true)->get();
foreach($configs as $config) {
    echo '  - Class: ' . $config->class . ', Year: ' . $config->year . ', Desc: ' . $config->description . ', Amount: GHS ' . $config->amount . PHP_EOL;
}
