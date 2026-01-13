$results = DB::select("SELECT academic_year, description, count(*) as count, sum(is_active) as active_count FROM dues GROUP BY academic_year, description ORDER BY academic_year DESC");
echo "--- DUES TYPES SUMMARY ---" . PHP_EOL;
foreach ($results as $row) {
    echo "Year: {$row->academic_year} | Desc: '{$row->description}' | Total: {$row->count} | Active: {$row->active_count}" . PHP_EOL;
}

$dups = DB::select("SELECT student_id, academic_year, description, COUNT(*) as c FROM dues WHERE is_active = 1 GROUP BY student_id, academic_year, description HAVING c > 1 LIMIT 5");
echo PHP_EOL . "--- DUPLICATES (is_active=1) ---" . PHP_EOL;
foreach ($dups as $row) {
    echo "Student: {$row->student_id} | Year: {$row->academic_year} | Desc: '{$row->description}' | Count: {$row->c}" . PHP_EOL;
}

$multiYears = DB::select("SELECT student_id, COUNT(DISTINCT academic_year) as year_count, SUM(amount) as total_balance FROM dues WHERE is_active = 1 AND (payment_status = 'owing' OR payment_status = 'pending_verification') GROUP BY student_id HAVING total_balance >= 140 LIMIT 5");
echo PHP_EOL . "--- STUDENTS WITH BALANCE >= 140 ---" . PHP_EOL;
foreach ($multiYears as $row) {
    $student = DB::table('users')->where('user_id', $row->student_id)->first();
    echo "Student: " . ($student->fullname ?? $student->username) . " (ID: {$row->student_id}) | Years: {$row->year_count} | Total: GHS {$row->total_balance}" . PHP_EOL;
    $details = DB::table('dues')->where('student_id', $row->student_id)->where('is_active', 1)->get();
    foreach($details as $d) {
        echo "  - Year: {$d->academic_year} | Desc: '{$d->description}' | Amount: {$d->amount} | Status: {$d->payment_status}" . PHP_EOL;
    }
}
