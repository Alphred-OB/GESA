<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DefaultDueConfig;
use App\Models\Due;
use App\Models\User;
use App\Services\Admin\AdminDueService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AdminDuesMaintenanceController extends Controller
{
    public function __construct(private readonly AdminDueService $dueService)
    {
    }

    /**
     * Show the dues maintenance dashboard.
     */
    public function index(): View
    {
        try {
            // Get all unique active dues (by academic_year + description) with payment breakdown
            $uniqueDues = Due::query()
                ->where('is_active', true)
                ->select('academic_year', 'description')
                ->selectRaw('COUNT(*) as student_count')
                ->selectRaw('SUM(amount) as total_amount')
                ->selectRaw('SUM(CASE WHEN payment_status = "paid" THEN 1 ELSE 0 END) as paid_count')
                ->selectRaw('SUM(CASE WHEN payment_status = "pending_verification" THEN 1 ELSE 0 END) as pending_count')
                ->selectRaw('SUM(CASE WHEN payment_status = "owing" THEN 1 ELSE 0 END) as owing_count')
                ->selectRaw('SUM(CASE WHEN payment_status = "paid" THEN amount ELSE 0 END) as collected_amount')
                ->groupBy('academic_year', 'description')
                ->orderByDesc('academic_year')
                ->orderByDesc('student_count')
                ->get();

            // Get total active students
            $totalStudents = User::query()
                ->where('role', 'student')
                ->where('is_graduated', false)
                ->whereNotNull('email_verified_at')
                ->count();

            // Find students missing each due
            $missingDues = [];
            foreach ($uniqueDues as $due) {
                $studentsWithDue = Due::query()
                    ->where('academic_year', $due->academic_year)
                    ->where('description', $due->description)
                    ->where('is_active', true)
                    ->pluck('student_id')
                    ->toArray();

                $missingCount = User::query()
                    ->where('role', 'student')
                    ->where('is_graduated', false)
                    ->whereNotNull('email_verified_at')
                    ->whereNotIn('user_id', $studentsWithDue)
                    ->count();

                if ($missingCount > 0) {
                    $missingDues[] = [
                        'academic_year' => $due->academic_year,
                        'description' => $due->description,
                        'missing_count' => $missingCount,
                        'has_count' => $due->student_count,
                        'paid_count' => $due->paid_count,
                        'pending_count' => $due->pending_count,
                        'owing_count' => $due->owing_count,
                    ];
                }
            }

            // Find duplicate dues (same student has same due multiple times)
            $duplicates = DB::table('dues')
                ->select('student_id', 'academic_year', 'description')
                ->selectRaw('COUNT(*) as duplicate_count')
                ->selectRaw('GROUP_CONCAT(due_id) as due_ids')
                ->where('is_active', true)
                ->groupBy('student_id', 'academic_year', 'description')
                ->havingRaw('COUNT(*) > 1')
                ->get()
                ->map(function ($row) {
                    $student = User::find($row->student_id);
                    return [
                        'student_id' => $row->student_id,
                        'student_name' => $student?->fullname ?? $student?->username ?? 'Student #' . $row->student_id,
                        'student_class' => $student?->class,
                        'student_year' => $student?->year,
                        'academic_year' => $row->academic_year,
                        'description' => $row->description,
                        'duplicate_count' => $row->duplicate_count,
                        'due_ids' => $row->due_ids,
                    ];
                });

            // Find orphaned dues (dues where student_id does not exist in users table)
            $orphanedCount = Due::query()
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('users')
                        ->whereColumn('users.user_id', 'dues.student_id');
                })
                ->count();

            return view('dashboards.admin.dues.maintenance', [
                'title' => 'Dues Maintenance',
                'uniqueDues' => $uniqueDues,
                'totalStudents' => $totalStudents,
                'missingDues' => $missingDues,
                'duplicates' => $duplicates,
                'orphanedCount' => $orphanedCount,
            ]);
        } catch (\Exception $e) {
            Log::error('Dues Maintenance: Failed to load dashboard', ['error' => $e->getMessage()]);
            return view('dashboards.admin.dues.maintenance', [
                'title' => 'Dues Maintenance',
                'uniqueDues' => collect(),
                'totalStudents' => 0,
                'missingDues' => [],
                'duplicates' => collect(),
                'orphanedCount' => 0,
                'loadError' => 'Failed to load maintenance data: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Delete all orphaned dues.
     */
    public function deleteAllOrphaned(): RedirectResponse
    {
        try {
            // Count first to show in message
            $orphanedQuery = Due::query()
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('users')
                        ->whereColumn('users.user_id', 'dues.student_id');
                });
            
            $count = $orphanedQuery->count();
            $orphanedQuery->delete();

            return redirect()->back()->with('status', "Successfully deleted $count orphaned dues.");
        } catch (\Exception $e) {
            Log::error('Dues Maintenance: Failed to delete orphaned dues', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to delete orphaned dues.');
        }
    }

    /**
     * Show detailed view of a specific due type.
     */
    public function showDueDetails(Request $request): View
    {
        $academicYear = $request->query('academic_year');
        $description = $request->query('description');

        if (!$academicYear || !$description) {
            abort(404, 'Academic year and description required');
        }

        // Get all dues of this type with student info
        $dues = Due::query()
            ->with('student:user_id,username,fullname,email,class,year,index_number')
            ->where('academic_year', $academicYear)
            ->where('description', $description)
            ->where('is_active', true)
            ->orderByRaw("CASE payment_status WHEN 'paid' THEN 1 WHEN 'pending_verification' THEN 2 ELSE 3 END")
            ->orderBy('created_at', 'desc')
            ->get();

        // Payment statistics
        $stats = [
            'total' => $dues->count(),
            'paid' => $dues->where('payment_status', 'paid')->count(),
            'pending' => $dues->where('payment_status', 'pending_verification')->count(),
            'owing' => $dues->where('payment_status', 'owing')->count(),
            'total_amount' => $dues->sum('amount'),
            'collected' => $dues->where('payment_status', 'paid')->sum('amount'),
        ];

        // Check if safe to delete (no payments made)
        $safeToDelete = $stats['paid'] === 0 && $stats['pending'] === 0;

        // Find students missing this due
        $studentsWithDue = $dues->pluck('student_id')->toArray();
        $missingStudents = User::query()
            ->where('role', 'student')
            ->where('is_graduated', false)
            ->whereNotNull('email_verified_at')
            ->whereNotIn('user_id', $studentsWithDue)
            ->select('user_id', 'username', 'fullname', 'email', 'class', 'year', 'index_number')
            ->orderBy('class')
            ->orderBy('year')
            ->orderBy('fullname')
            ->get();

        return view('dashboards.admin.dues.due-details', [
            'title' => $description . ' - Details',
            'academicYear' => $academicYear,
            'description' => $description,
            'dues' => $dues,
            'stats' => $stats,
            'safeToDelete' => $safeToDelete,
            'missingStudents' => $missingStudents,
        ]);
    }

    /**
     * Edit a due record.
     */
    public function editDue(Request $request, Due $due): RedirectResponse
    {
        // Cannot edit paid dues
        if ($due->payment_status === 'paid') {
            return back()->with('error', 'Cannot edit a paid due.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
            'description' => 'required|string|max:255',
        ]);

        try {
            $due->update($validated);

            Log::info('Dues Maintenance: Edited due', [
                'due_id' => $due->due_id,
                'changes' => $validated,
                'admin_id' => $request->user('admin')?->user_id,
            ]);

            return back()->with('status', 'Due updated successfully.');
        } catch (\Exception $e) {
            Log::error('Dues Maintenance: Failed to edit due', ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Sync dues for students who are missing them.
     */
    public function syncMissing(Request $request): RedirectResponse
    {
        $academicYear = $request->input('academic_year');
        $description = $request->input('description');

        if (!$academicYear || !$description) {
            return back()->with('error', 'Academic year and description are required.');
        }

        try {
            $count = DB::transaction(function () use ($academicYear, $description, $request) {
                // Get students who already have this due
                $studentsWithDue = Due::query()
                    ->where('academic_year', $academicYear)
                    ->where('description', $description)
                    ->pluck('student_id')
                    ->toArray();

                // Get a reference due to copy from
                $referenceDue = Due::query()
                    ->where('academic_year', $academicYear)
                    ->where('description', $description)
                    ->where('is_active', true)
                    ->first();

                if (!$referenceDue) {
                    throw new \RuntimeException('No reference due found.');
                }

                // Get students missing this due
                $studentsNeedingDue = User::query()
                    ->where('role', 'student')
                    ->where('is_graduated', false)
                    ->whereNotNull('email_verified_at')
                    ->whereNotIn('user_id', $studentsWithDue)
                    ->get();

                $count = 0;
                foreach ($studentsNeedingDue as $student) {
                    // Double-check to prevent race conditions
                    $exists = Due::where('student_id', $student->user_id)
                        ->where('academic_year', $academicYear)
                        ->where('description', $description)
                        ->exists();

                    if (!$exists) {
                        Due::create([
                            'student_id' => $student->user_id,
                            'description' => $description,
                            'amount' => $referenceDue->amount,
                            'due_date' => $referenceDue->due_date,
                            'academic_year' => $academicYear,
                            'payment_status' => 'owing',
                            'is_active' => true,
                            'recorded_by' => $request->user('admin')?->user_id,
                        ]);
                        $count++;
                    }
                }

                return $count;
            });

            Log::info('Dues Maintenance: Synced missing dues', [
                'academic_year' => $academicYear,
                'description' => $description,
                'count' => $count,
                'admin_id' => $request->user('admin')?->user_id,
            ]);

            return back()->with('status', "Successfully assigned '{$description}' to {$count} students.");
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Dues Maintenance: Failed to sync missing dues', [
                'error' => $e->getMessage(),
                'academic_year' => $academicYear,
                'description' => $description,
            ]);
            return back()->with('error', 'An error occurred while syncing dues. Please try again.');
        }
    }

    /**
     * Sync all students - assign all active dues to students who don't have them.
     */
    public function syncAll(Request $request): RedirectResponse
    {
        try {
            $students = User::query()
                ->where('role', 'student')
                ->where('is_graduated', false)
                ->whereNotNull('email_verified_at')
                ->get();

            $syncedCount = 0;
            $duesAssigned = 0;

            foreach ($students as $student) {
                $beforeCount = Due::where('student_id', $student->user_id)->count();
                $this->dueService->syncStudent($student);
                $afterCount = Due::where('student_id', $student->user_id)->count();
                
                if ($afterCount > $beforeCount) {
                    $syncedCount++;
                    $duesAssigned += ($afterCount - $beforeCount);
                }
            }

            Log::info('Dues Maintenance: Synced all students', [
                'synced_count' => $syncedCount,
                'dues_assigned' => $duesAssigned,
                'admin_id' => $request->user('admin')?->user_id,
            ]);

            return back()->with('status', "Synced {$syncedCount} students. Assigned {$duesAssigned} new dues.");
        } catch (\Exception $e) {
            Log::error('Dues Maintenance: Failed to sync all students', ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while syncing students. Please try again.');
        }
    }

    /**
     * Delete duplicate dues, keeping only the paid/first one.
     */
    public function deleteDuplicate(Request $request): RedirectResponse
    {
        $dueIds = $request->input('due_ids');
        
        if (!$dueIds) {
            return back()->with('error', 'No due IDs provided.');
        }

        try {
            $result = DB::transaction(function () use ($dueIds) {
                $ids = explode(',', $dueIds);
                $dues = Due::whereIn('due_id', $ids)->get();

                if ($dues->isEmpty()) {
                    throw new \RuntimeException('No dues found.');
                }

                // Verify these are actually duplicates
                $firstDue = $dues->first();
                $allSame = $dues->every(fn ($due) => 
                    $due->student_id === $firstDue->student_id &&
                    $due->academic_year === $firstDue->academic_year &&
                    $due->description === $firstDue->description
                );

                if (!$allSame) {
                    throw new \RuntimeException('These dues are not duplicates of each other.');
                }

                // Keep the paid one if any
                $toKeep = $dues->firstWhere('payment_status', 'paid') 
                       ?? $dues->firstWhere('payment_status', 'pending_verification')
                       ?? $dues->first();

                $deleted = 0;
                foreach ($dues as $due) {
                    if ($due->due_id !== $toKeep->due_id) {
                        $due->delete();
                        $deleted++;
                    }
                }

                return [
                    'deleted' => $deleted,
                    'kept_id' => $toKeep->due_id,
                    'kept_status' => $toKeep->payment_status,
                ];
            });

            Log::info('Dues Maintenance: Deleted duplicates', $result);

            return back()->with('status', "Deleted {$result['deleted']} duplicate(s), kept due #{$result['kept_id']} (status: {$result['kept_status']}).");
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Dues Maintenance: Failed to delete duplicates', ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Delete all duplicates at once.
     */
    public function deleteAllDuplicates(Request $request): RedirectResponse
    {
        try {
            $totalDeleted = DB::transaction(function () {
                $duplicates = DB::table('dues')
                    ->select('student_id', 'academic_year', 'description')
                    ->selectRaw('GROUP_CONCAT(due_id ORDER BY 
                        CASE payment_status 
                            WHEN "paid" THEN 1 
                            WHEN "pending_verification" THEN 2 
                            ELSE 3 
                        END, due_id) as due_ids')
                    ->where('is_active', true)
                    ->groupBy('student_id', 'academic_year', 'description')
                    ->havingRaw('COUNT(*) > 1')
                    ->get();

                $totalDeleted = 0;

                foreach ($duplicates as $row) {
                    $ids = explode(',', $row->due_ids);
                    $keepId = array_shift($ids);
                    
                    if (!empty($ids)) {
                        $deleted = Due::whereIn('due_id', $ids)->delete();
                        $totalDeleted += $deleted;
                    }
                }

                return $totalDeleted;
            });

            Log::info('Dues Maintenance: Deleted all duplicates', [
                'total_deleted' => $totalDeleted,
                'admin_id' => $request->user('admin')?->user_id,
            ]);

            return back()->with('status', "Deleted {$totalDeleted} duplicate dues across all students.");
        } catch (\Exception $e) {
            Log::error('Dues Maintenance: Failed to delete all duplicates', ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Merge one due type into another.
     * Transfers payments from source to target, then deletes source.
     */
    public function mergeDues(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'source_academic_year' => 'required|string',
            'source_description' => 'required|string',
            'target_academic_year' => 'required|string',
            'target_description' => 'required|string',
        ]);

        $sourceYear = $validated['source_academic_year'];
        $sourceDesc = $validated['source_description'];
        $targetYear = $validated['target_academic_year'];
        $targetDesc = $validated['target_description'];

        // Can't merge into self
        if ($sourceYear === $targetYear && $sourceDesc === $targetDesc) {
            return back()->with('error', 'Cannot merge a due into itself.');
        }

        try {
            $result = DB::transaction(function () use ($sourceYear, $sourceDesc, $targetYear, $targetDesc, $request) {
                // Get all source dues
                $sourceDues = Due::query()
                    ->where('academic_year', $sourceYear)
                    ->where('description', $sourceDesc)
                    ->get();

                if ($sourceDues->isEmpty()) {
                    throw new \RuntimeException('Source due type not found.');
                }

                // Get a reference for target due amount/date
                $targetReference = Due::query()
                    ->where('academic_year', $targetYear)
                    ->where('description', $targetDesc)
                    ->where('is_active', true)
                    ->first();

                if (!$targetReference) {
                    throw new \RuntimeException('Target due type not found.');
                }

                $transferred = 0;
                $deleted = 0;
                $created = 0;

                foreach ($sourceDues as $sourceDue) {
                    // Check if student already has target due
                    $existingTargetDue = Due::query()
                        ->where('student_id', $sourceDue->student_id)
                        ->where('academic_year', $targetYear)
                        ->where('description', $targetDesc)
                        ->first();

                    if ($sourceDue->payment_status === 'paid' || $sourceDue->payment_status === 'pending_verification') {
                        // Student paid for source due - need to transfer payment
                        if ($existingTargetDue) {
                            // Update existing target due with payment info from source
                            if ($existingTargetDue->payment_status !== 'paid') {
                                $existingTargetDue->update([
                                    'payment_status' => $sourceDue->payment_status,
                                    'payment_method' => $sourceDue->payment_method,
                                    'payment_reference' => $sourceDue->payment_reference,
                                    'payment_date' => $sourceDue->payment_date,
                                    'verification_date' => $sourceDue->verification_date,
                                    'verified_by' => $sourceDue->verified_by,
                                    'verification_notes' => $sourceDue->verification_notes,
                                    'network' => $sourceDue->network,
                                    'reference_number' => $sourceDue->reference_number,
                                    'payment_notes' => ($sourceDue->payment_notes ? $sourceDue->payment_notes . ' | ' : '') . 'Merged from: ' . $sourceDesc,
                                ]);
                                $transferred++;
                            }
                        } else {
                            // Create target due as paid (copy payment info)
                            Due::create([
                                'student_id' => $sourceDue->student_id,
                                'description' => $targetDesc,
                                'amount' => $targetReference->amount,
                                'due_date' => $targetReference->due_date,
                                'academic_year' => $targetYear,
                                'payment_status' => $sourceDue->payment_status,
                                'payment_method' => $sourceDue->payment_method,
                                'payment_reference' => $sourceDue->payment_reference,
                                'payment_date' => $sourceDue->payment_date,
                                'verification_date' => $sourceDue->verification_date,
                                'verified_by' => $sourceDue->verified_by,
                                'verification_notes' => $sourceDue->verification_notes,
                                'is_active' => true,
                                'network' => $sourceDue->network,
                                'reference_number' => $sourceDue->reference_number,
                                'payment_notes' => 'Merged from: ' . $sourceDesc,
                                'recorded_by' => $sourceDue->recorded_by,
                            ]);
                            $created++;
                            $transferred++;
                        }
                    } else {
                        // Student has owing source due
                        if (!$existingTargetDue) {
                            // Create target due as owing
                            Due::create([
                                'student_id' => $sourceDue->student_id,
                                'description' => $targetDesc,
                                'amount' => $targetReference->amount,
                                'due_date' => $targetReference->due_date,
                                'academic_year' => $targetYear,
                                'payment_status' => 'owing',
                                'is_active' => true,
                                'recorded_by' => $sourceDue->recorded_by,
                            ]);
                            $created++;
                        }
                    }

                    // Delete source due
                    $sourceDue->delete();
                    $deleted++;
                }

                return [
                    'transferred' => $transferred,
                    'deleted' => $deleted,
                    'created' => $created,
                ];
            });

            Log::info('Dues Maintenance: Merged dues', [
                'source' => $sourceDesc,
                'target' => $targetDesc,
                'result' => $result,
                'admin_id' => $request->user('admin')?->user_id,
            ]);

            return redirect()->route('admin.dues.maintenance.index')
                ->with('status', "Successfully merged '{$sourceDesc}' into '{$targetDesc}'. Transferred {$result['transferred']} payments, created {$result['created']} new dues, deleted {$result['deleted']} source dues.");

        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Dues Maintenance: Failed to merge dues', [
                'error' => $e->getMessage(),
                'source' => $sourceDesc ?? 'unknown',
                'target' => $targetDesc ?? 'unknown',
            ]);
            return back()->with('error', 'An error occurred while merging dues: ' . $e->getMessage());
        }
    }

    /**
     * Show the merge confirmation page.
     */
    public function showMerge(Request $request): View
    {
        $sourceYear = $request->query('source_academic_year');
        $sourceDesc = $request->query('source_description');

        if (!$sourceYear || !$sourceDesc) {
            abort(404, 'Source due required');
        }

        // Get source stats
        $sourceStats = Due::query()
            ->where('academic_year', $sourceYear)
            ->where('description', $sourceDesc)
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN payment_status = "paid" THEN 1 ELSE 0 END) as paid')
            ->selectRaw('SUM(CASE WHEN payment_status = "pending_verification" THEN 1 ELSE 0 END) as pending')
            ->selectRaw('SUM(CASE WHEN payment_status = "owing" THEN 1 ELSE 0 END) as owing')
            ->selectRaw('SUM(CASE WHEN payment_status = "paid" THEN amount ELSE 0 END) as collected')
            ->first();

        // Get all other dues in same academic year as potential targets
        $potentialTargets = Due::query()
            ->where('academic_year', $sourceYear)
            ->where('description', '!=', $sourceDesc)
            ->where('is_active', true)
            ->select('academic_year', 'description')
            ->selectRaw('COUNT(*) as student_count')
            ->groupBy('academic_year', 'description')
            ->get();

        return view('dashboards.admin.dues.merge', [
            'title' => 'Merge Dues',
            'sourceYear' => $sourceYear,
            'sourceDesc' => $sourceDesc,
            'sourceStats' => $sourceStats,
            'potentialTargets' => $potentialTargets,
        ]);
    }

    /**
     * Show the edit amounts page for a due type (grouped by class/year).
     * Shows ALL classes and years, even without students.
     */
    public function showEditAmounts(Request $request): View
    {
        $academicYear = $request->query('academic_year');
        $description = $request->query('description');

        if (!$academicYear || !$description) {
            abort(404, 'Due type required');
        }

        // Get all classes and years from the matrix
        $matrix = $this->dueService->matrix();
        $allClasses = $matrix['classes'];
        $allYears = $matrix['years'];

        // Get current amounts and stats by class/year from existing dues
        $amountsByClassYear = Due::query()
            ->join('users', 'dues.student_id', '=', 'users.user_id')
            ->where('dues.academic_year', $academicYear)
            ->where('dues.description', $description)
            ->where('dues.is_active', true)
            ->select('users.class', 'users.year')
            ->selectRaw('dues.amount')
            ->selectRaw('COUNT(*) as student_count')
            ->selectRaw('SUM(CASE WHEN dues.payment_status = "paid" THEN 1 ELSE 0 END) as paid_count')
            ->selectRaw('SUM(CASE WHEN dues.payment_status = "pending_verification" THEN 1 ELSE 0 END) as pending_count')
            ->selectRaw('SUM(CASE WHEN dues.payment_status = "owing" THEN 1 ELSE 0 END) as owing_count')
            ->groupBy('users.class', 'users.year', 'dues.amount')
            ->get()
            ->keyBy(fn($item) => strtolower(trim($item->class)) . '|' . trim((string)$item->year));

        // Get DefaultDueConfig for cells without students (for future registrations)
        $configByClassYear = DefaultDueConfig::query()
            ->where('description', $description)
            ->where('is_active', true)
            ->get()
            ->keyBy(fn($config) => strtolower(trim($config->class)) . '|' . trim((string)$config->year));

        // Build matrix with all classes and years
        $classYearMatrix = [];
        foreach ($allClasses as $class) {
            foreach ($allYears as $year) {
                // Use lowercase trimmed keys for lookup to handle potential case/whitespace issues
                $key = strtolower(trim($class)) . '|' . trim((string)$year);
                $existing = $amountsByClassYear->get($key);
                $config = $configByClassYear->get($key);
                
                // Use dues amount if students exist, otherwise use config amount
                $amount = $existing->amount ?? $config->amount ?? null;
                
                $classYearMatrix[$class][$year] = [
                    'class' => $class,
                    'year' => $year,
                    'amount' => $amount,
                    'student_count' => $existing->student_count ?? 0,
                    'paid_count' => $existing->paid_count ?? 0,
                    'pending_count' => $existing->pending_count ?? 0,
                    'owing_count' => $existing->owing_count ?? 0,
                ];
            }
        }

        // Get a reference due to show default amount
        $referenceDue = Due::query()
            ->where('academic_year', $academicYear)
            ->where('description', $description)
            ->where('is_active', true)
            ->first();

        return view('dashboards.admin.dues.edit-amounts', [
            'title' => 'Edit Due Amounts',
            'academicYear' => $academicYear,
            'description' => $description,
            'allClasses' => $allClasses,
            'allYears' => $allYears,
            'classYearMatrix' => $classYearMatrix,
            'defaultAmount' => $referenceDue->amount ?? 0,
        ]);
    }

    /**
     * Update amounts for specific class/year combinations.
     */
    public function updateAmounts(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'academic_year' => 'required|string',
            'description' => 'required|string',
            'updates' => 'required|array',
            'updates.*.class' => 'required|string',
            'updates.*.year' => 'required|integer|min:1|max:4',
            'updates.*.new_amount' => 'required|numeric|min:0',
        ]);

        $academicYear = $validated['academic_year'];
        $description = $validated['description'];
        $updates = $validated['updates'];

        try {
            $result = DB::transaction(function () use ($academicYear, $description, $updates, $request) {
                $totalUpdated = 0;
                $skippedPaid = 0;

                foreach ($updates as $update) {
                    $class = $update['class'];
                    $year = $update['year'];
                    $newAmount = $update['new_amount'];

                    // Get all student IDs in this class/year
                    $studentIds = User::query()
                        ->where('role', 'student')
                        ->where('class', $class)
                        ->where('year', $year)
                        ->pluck('user_id')
                        ->toArray();

                    if (empty($studentIds)) {
                        continue;
                    }

                    // Update ONLY owing dues (protect paid and pending)
                    $updated = Due::query()
                        ->whereIn('student_id', $studentIds)
                        ->where('academic_year', $academicYear)
                        ->where('description', $description)
                        ->where('payment_status', 'owing')  // Only update owing dues
                        ->update(['amount' => $newAmount]);

                    $totalUpdated += $updated;

                    // Count how many paid/pending were skipped
                    $paidPendingCount = Due::query()
                        ->whereIn('student_id', $studentIds)
                        ->where('academic_year', $academicYear)
                        ->where('description', $description)
                        ->whereIn('payment_status', ['paid', 'pending_verification'])
                        ->count();

                    $skippedPaid += $paidPendingCount;
                }

                return [
                    'updated' => $totalUpdated,
                    'skipped' => $skippedPaid,
                ];
            });

            Log::info('Dues Maintenance: Updated amounts by class/year', [
                'academic_year' => $academicYear,
                'description' => $description,
                'updates' => $updates,
                'result' => $result,
                'admin_id' => $request->user('admin')?->user_id,
            ]);

            $message = "Updated {$result['updated']} dues.";
            if ($result['skipped'] > 0) {
                $message .= " Skipped {$result['skipped']} paid/pending dues (amounts preserved).";
            }

            return redirect()->route('admin.dues.maintenance.edit-amounts', [
                'academic_year' => $academicYear,
                'description' => $description,
            ])->with('status', $message);

        } catch (\Exception $e) {
            Log::error('Dues Maintenance: Failed to update amounts', [
                'error' => $e->getMessage(),
                'academic_year' => $academicYear,
                'description' => $description,
            ]);
            return back()->with('error', 'Failed to update amounts: ' . $e->getMessage());
        }
    }

    /**
     * Update amount for a single class/year (AJAX-friendly).
     */
    public function updateSingleAmount(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'academic_year' => 'required|string',
            'description' => 'required|string',
            'class' => 'required|string',
            'year' => 'required|integer|min:1|max:4',
            'new_amount' => 'required|numeric|min:0',
        ]);

        try {
            // Get all student IDs in this class/year
            $studentIds = User::query()
                ->where('role', 'student')
                ->where('class', $validated['class'])
                ->where('year', $validated['year'])
                ->pluck('user_id')
                ->toArray();

            if (empty($studentIds)) {
                return back()->with('error', 'No students found in this class/year.');
            }

            // Update ONLY owing dues
            $updated = Due::query()
                ->whereIn('student_id', $studentIds)
                ->where('academic_year', $validated['academic_year'])
                ->where('description', $validated['description'])
                ->where('payment_status', 'owing')
                ->update(['amount' => $validated['new_amount']]);

            // Count skipped
            $skipped = Due::query()
                ->whereIn('student_id', $studentIds)
                ->where('academic_year', $validated['academic_year'])
                ->where('description', $validated['description'])
                ->whereIn('payment_status', ['paid', 'pending_verification'])
                ->count();

            Log::info('Dues Maintenance: Updated single class/year amount', [
                'class' => $validated['class'],
                'year' => $validated['year'],
                'new_amount' => $validated['new_amount'],
                'updated' => $updated,
                'skipped' => $skipped,
                'admin_id' => $request->user('admin')?->user_id,
            ]);

            $message = "Updated {$updated} dues for {$validated['class']} Year {$validated['year']} to GHS " . number_format($validated['new_amount'], 2);
            if ($skipped > 0) {
                $message .= " ({$skipped} paid/pending skipped)";
            }

            return back()->with('status', $message);

        } catch (\Exception $e) {
            Log::error('Dues Maintenance: Failed to update single amount', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to update: ' . $e->getMessage());
        }
    }

    /**
     * Update ALL amounts from the matrix form at once.
     */
    public function updateAllAmounts(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'academic_year' => 'required|string',
            'description' => 'required|string',
            'amounts' => 'required|array',
        ]);

        $academicYear = $validated['academic_year'];
        $description = $validated['description'];
        $amounts = $validated['amounts']; // Format: amounts[class][year] = amount

        try {
            $result = DB::transaction(function () use ($academicYear, $description, $amounts, $request) {
                $totalUpdated = 0;
                $configUpdated = 0;

                foreach ($amounts as $class => $years) {
                    foreach ($years as $year => $newAmount) {
                        if ($newAmount === null || $newAmount === '') {
                            continue;
                        }

                        $floatAmount = (float) $newAmount;

                        // Update or create DefaultDueConfig for future students
                        // We trim class/year to ensure the config is clean, even if user data has whitespace
                        DefaultDueConfig::updateOrCreate(
                            [
                                'class' => trim($class),
                                'year' => trim((string) $year),
                                'description' => $description,
                            ],
                            [
                                'amount' => $floatAmount,
                                'is_active' => true,
                                'target_group' => 'student',
                            ]
                        );

                        $configUpdated++;

                        // Get all student IDs in this class/year
                        // Use original $class/$year to match existing user records exactly
                        $studentIds = User::query()
                            ->where('role', 'student')
                            ->where('class', $class)
                            ->where('year', $year)
                            ->pluck('user_id')
                            ->toArray();

                        if (empty($studentIds)) {
                            continue;
                        }

                        // Update ALL dues (including paid and pending)
                        $updated = Due::query()
                            ->whereIn('student_id', $studentIds)
                            ->where('academic_year', $academicYear)
                            ->where('description', $description)
                            ->update(['amount' => $floatAmount]);

                        $totalUpdated += $updated;
                    }
                }

                return [
                    'updated' => $totalUpdated,
                    'configUpdated' => $configUpdated,
                ];
            });

            Log::info('Dues Maintenance: Updated all amounts', [
                'academic_year' => $academicYear,
                'description' => $description,
                'result' => $result,
                'admin_id' => $request->user('admin')?->user_id,
            ]);

            $message = "Successfully updated {$result['updated']} dues.";
            if ($result['configUpdated'] > 0) {
                $message .= " Also updated {$result['configUpdated']} config entries for future students.";
            }

            return redirect()->route('admin.dues.maintenance.edit-amounts', [
                'academic_year' => $academicYear,
                'description' => $description,
            ])->with('status', $message);

        } catch (\Exception $e) {
            Log::error('Dues Maintenance: Failed to update all amounts', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to update amounts: ' . $e->getMessage());
        }
    }
}


