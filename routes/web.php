<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\StudentPaystackPaymentController;
use App\Http\Controllers\Student\StudentProfileController;
use App\Http\Controllers\Admin\AdminCourseRegistrationController;
use App\Http\Controllers\Admin\AdminAnnouncementController;
use App\Http\Controllers\Admin\AdminSuggestionController;
use App\Http\Controllers\Admin\AdminDueController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminAcademicTimelineController;
use App\Http\Controllers\Admin\AdminPersonalDueController;

Route::get('/', function () {
    if (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }

    if (Auth::guard('student')->check()) {
        return redirect()->route('student.dashboard');
    }

    return redirect()->route('login');
})->name('home');

Route::view('/legal/terms', 'legal.terms')->name('legal.terms');
Route::view('/legal/privacy', 'legal.privacy')->name('legal.privacy');
Route::view('/legal/cookies', 'legal.cookies')->name('legal.cookies');

Route::view('/developers', 'developers')->name('marketing.developers');
Route::view('/legal/accessibility', 'legal.accessibility')->name('legal.accessibility');

// Generic availability check (public API for registration forms)
Route::post('/api/check-availability', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'field' => 'required|in:username,email,index_number,phone_number',
        'value' => 'required|string|max:100',
    ]);
    
    $field = $request->field;
    $value = trim($request->value);
    
    if ($field === 'username' || $field === 'email') {
        $value = strtolower($value);
    }
    
    // Check in users table
    $existsInUsers = \App\Models\User::where($field, $value)->exists();
    
    // Check in pending registrations (only pending ones)
    $existsInPending = \App\Models\PendingRegistration::where($field, $value)
        ->where('status', 'pending')
        ->exists();
    
    $available = !$existsInUsers && !$existsInPending;
    
    $fieldNames = [
        'username' => 'Username',
        'email' => 'Email address',
        'index_number' => 'Reference number',
        'phone_number' => 'Phone number'
    ];
    
    return response()->json([
        'available' => $available,
        'message' => $available 
            ? ($fieldNames[$field] . ' is available') 
            : ($fieldNames[$field] . ' is already taken')
    ]);
})->name('api.check-availability')->middleware('throttle:30,1');


Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])
        ->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('auth.login.submit');

    Route::get('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])
        ->middleware('throttle:3,1')
        ->name('password.email');

    Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\Auth\NewPasswordController::class, 'store'])
        ->name('password.update');

    Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'create'])
        ->name('auth.register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'store'])
        ->middleware('throttle:3,1')
        ->name('auth.register.submit');

    // REMOVED: Emergency cache clearer should not be in production routes.

    // Fresher Registration (for students without student email access)
    Route::get('/register/fresher', [\App\Http\Controllers\Auth\FresherRegisterController::class, 'create'])
        ->name('auth.fresher-register');
    Route::post('/register/fresher', [\App\Http\Controllers\Auth\FresherRegisterController::class, 'store'])
        ->name('auth.fresher-register.submit');

    Route::get('/register/fresher/verify', [\App\Http\Controllers\Auth\FresherRegisterController::class, 'showVerifyForm'])
        ->name('auth.fresher-register.verify');
    Route::post('/register/fresher/verify', [\App\Http\Controllers\Auth\FresherRegisterController::class, 'verify'])
        ->name('auth.fresher-register.verify.submit');
    Route::post('/register/fresher/verify/resend', [\App\Http\Controllers\Auth\FresherRegisterController::class, 'resend'])
        ->name('auth.fresher-register.resend');

    Route::get('/register/fresher/success', [\App\Http\Controllers\Auth\FresherRegisterController::class, 'success'])
        ->name('auth.fresher-register.success');

    Route::get('/verify-email', [\App\Http\Controllers\Auth\EmailVerificationController::class, 'notice'])
        ->name('auth.verify.notice');
    Route::post('/verify-email', [\App\Http\Controllers\Auth\EmailVerificationController::class, 'verify'])
        ->name('auth.verify.submit');
    Route::post('/verify-email/resend', [\App\Http\Controllers\Auth\EmailVerificationController::class, 'resend'])
        ->name('auth.verify.resend');
    Route::get('/verify-email/resend', function () {
        return redirect()->route('auth.verify.notice');
    });

    Route::get('/login/otp', [\App\Http\Controllers\Auth\LoginOtpController::class, 'create'])
        ->name('auth.login.otp');
    Route::post('/login/otp', [\App\Http\Controllers\Auth\LoginOtpController::class, 'store'])
        ->name('auth.login.otp.submit');
    Route::post('/login/otp/resend', [\App\Http\Controllers\Auth\LoginOtpController::class, 'resend'])
        ->name('auth.login.otp.resend');
});

Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('auth.logout');

Route::get('/student/dashboard', StudentDashboardController::class)
    ->middleware(['auth:student', 'student.dues-gate'])
    ->name('student.dashboard');

Route::get('/student/profile', [StudentProfileController::class, 'show'])
    ->middleware('auth:student')
    ->name('student.profile');

Route::post('/student/profile', [StudentProfileController::class, 'update'])
    ->middleware('auth:student')
    ->name('student.profile.update');

Route::get('/student/profile/verify-email/{user}/{token}', [StudentProfileController::class, 'verifyEmail'])
    ->middleware('signed')
    ->name('student.profile.verify-email');

Route::get('/student/suggestions', [\App\Http\Controllers\Student\StudentSuggestionController::class, 'index'])
    ->middleware(['auth:student', 'student.dues-gate'])
    ->name('student.suggestions.index');

Route::post('/student/suggestions', [\App\Http\Controllers\Student\StudentSuggestionController::class, 'store'])
    ->middleware(['auth:student', 'student.dues-gate'])
    ->name('student.suggestions.store');

Route::get('/student/announcements', [\App\Http\Controllers\Student\StudentAnnouncementController::class, 'index'])
    ->middleware(['auth:student', 'student.dues-gate'])
    ->name('student.announcements.index');

Route::get('/student/announcements/{announcement:slug}', [\App\Http\Controllers\Student\StudentAnnouncementController::class, 'show'])
    ->middleware(['auth:student', 'student.dues-gate'])
    ->name('student.announcements.show');

Route::get('/student/events', [\App\Http\Controllers\Student\StudentEventController::class, 'index'])
    ->middleware(['auth:student', 'student.dues-gate'])
    ->name('student.events.index');

Route::get('/student/events/{event}', [\App\Http\Controllers\Student\StudentEventController::class, 'show'])
    ->middleware(['auth:student', 'student.dues-gate'])
    ->name('student.events.show');

Route::get('/student/events/{event}/ics', [\App\Http\Controllers\Student\StudentEventController::class, 'ics'])
    ->middleware(['auth:student', 'student.dues-gate'])
    ->name('student.events.ics');

Route::get('/student/resources', [\App\Http\Controllers\Student\StudentResourceController::class, 'index'])
    ->middleware(['auth:student', 'student.dues-gate'])
    ->name('student.resources.index');

Route::get('/student/dues', [\App\Http\Controllers\Student\StudentDueController::class, 'index'])
    ->middleware('auth:student')
    ->name('student.dues.index');

Route::post('/student/dues/{due}/paystack', [StudentPaystackPaymentController::class, 'initialize'])
    ->middleware('auth:student')
    ->name('student.payments.paystack.initialize');

Route::get('/student/payments/paystack/callback', [StudentPaystackPaymentController::class, 'callback'])
    ->middleware('auth:student')
    ->name('student.payments.paystack.callback');

Route::get('/student/dues/{due}/receipt', [StudentPaystackPaymentController::class, 'receipt'])
    ->middleware('auth:student')
    ->name('student.payments.paystack.receipt');

// RushPay Integration
Route::post('/student/dues/{due}/rushpay', [\App\Http\Controllers\Student\StudentRushPayPaymentController::class, 'initialize'])
    ->middleware('auth:student')
    ->name('student.payments.rushpay.initialize');

Route::get('/student/payments/rushpay/checkout/{reference}', [\App\Http\Controllers\Student\StudentRushPayPaymentController::class, 'checkout'])
    ->middleware('auth:student')
    ->name('student.payments.rushpay.checkout');

Route::get('/student/payments/rushpay/callback', [\App\Http\Controllers\Student\StudentRushPayPaymentController::class, 'callback'])
    ->middleware('auth:student')
    ->name('student.payments.rushpay.callback');

Route::post('/student/dues/{due}/cancel', [\App\Http\Controllers\Student\StudentDueController::class, 'cancel'])
    ->middleware('auth:student')
    ->name('student.payments.cancel');

Route::get('/student/dues/{due}/manual', [\App\Http\Controllers\Student\StudentManualPaymentController::class, 'show'])
    ->middleware('auth:student')
    ->name('student.payments.manual.show');

Route::post('/student/dues/{due}/manual', [\App\Http\Controllers\Student\StudentManualPaymentController::class, 'store'])
    ->middleware('auth:student')
    ->name('student.payments.manual.store');

Route::get('/student/course-registration', [\App\Http\Controllers\Student\StudentCourseRegistrationController::class, 'show'])
    ->middleware('auth:student')
    ->name('student.course-registration.show');

Route::post('/student/course-registration', [\App\Http\Controllers\Student\StudentCourseRegistrationController::class, 'store'])
    ->middleware('auth:student')
    ->name('student.course-registration.store');

Route::delete('/student/course-registration/documents/{registration}/{encodedPath}', [\App\Http\Controllers\Student\StudentCourseRegistrationController::class, 'destroyDocument'])
    ->middleware('auth:student')
    ->name('student.course-registration.documents.destroy');

Route::get('/admin/dashboard', \App\Http\Controllers\Admin\AdminDashboardController::class)
    ->middleware('auth:admin')
    ->name('admin.dashboard');

Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('events', \App\Http\Controllers\Admin\AdminEventController::class)
        ->except(['show'])
        ->middleware('admin.role:general_secretary');

    Route::resource('resources', \App\Http\Controllers\Admin\AdminResourceController::class)
        ->except(['show'])
        ->middleware('admin.role:general_secretary');

    Route::resource('announcements', AdminAnnouncementController::class)
        ->except(['show'])
        ->middleware('admin.role:general_secretary');

    Route::resource('timeline', AdminAcademicTimelineController::class)
        ->except(['show'])
        ->middleware('admin.role:general_secretary');

    Route::get('dues/export', [AdminDueController::class, 'export'])
        ->name('dues.export')
        ->middleware('admin.role:financial_secretary');
    Route::post('students/promote-years', [\App\Http\Controllers\Admin\AdminStudentAccountController::class, 'promoteYears'])->name('students.promote-years');
    Route::get('dues/statistics', [AdminDueController::class, 'statistics'])
        ->name('dues.statistics')
        ->middleware('admin.role:financial_secretary');
    
    Route::get('dues/verifications', [AdminDueController::class, 'verifications'])
        ->name('dues.verifications')
        ->middleware('admin.role:financial_secretary');

    // Live polling API for pending verifications (returns JSON)
    Route::get('api/dues/pending-verifications', [AdminDueController::class, 'pendingVerificationsApi'])
        ->name('api.dues.pending-verifications')
        ->middleware('admin.role:financial_secretary');

    Route::get('dues/payment-settings', [\App\Http\Controllers\Admin\AdminPaymentSettingController::class, 'index'])
        ->name('payment-settings.index')
        ->middleware('admin.role:financial_secretary,president');
    Route::put('dues/payment-settings', [\App\Http\Controllers\Admin\AdminPaymentSettingController::class, 'update'])
        ->name('payment-settings.update')
        ->middleware('admin.role:financial_secretary,president');

    Route::get('verify-payment/{due}', [AdminDueController::class, 'verify'])
        ->name('dues.verify-payment')
        ->middleware('admin.role:financial_secretary');
    Route::post('dues/{due}/approve', [AdminDueController::class, 'approve'])
        ->name('dues.approve')
        ->middleware('admin.role:financial_secretary');
    Route::post('dues/{due}/reject', [AdminDueController::class, 'reject'])
        ->name('dues.reject')
        ->middleware('admin.role:financial_secretary');

    Route::get('dues/{due}/receipt', [AdminDueController::class, 'receipt'])
        ->name('dues.receipt')
        ->middleware('admin.role:financial_secretary');


    Route::resource('dues', AdminDueController::class)
        ->except(['show', 'destroy'])
        ->middleware('admin.role:financial_secretary');

    // Admin Personal Dues
    Route::get('personal-dues', [AdminPersonalDueController::class, 'index'])->name('personal-dues.index');
    Route::get('personal-dues/{due}/manual', [AdminPersonalDueController::class, 'showManualPayment'])->name('personal-dues.manual.show');
    Route::post('personal-dues/{due}/manual', [AdminPersonalDueController::class, 'storeManualPayment'])->name('personal-dues.manual.store');
    Route::get('personal-dues/{due}/receipt', [AdminPersonalDueController::class, 'receipt'])->name('personal-dues.receipt');

    Route::post('personal-dues/{due}/rushpay', [\App\Http\Controllers\Admin\AdminRushPayPaymentController::class, 'initialize'])->name('personal-dues.rushpay.initialize');
    Route::get('personal-dues/rushpay/checkout/{reference}', [\App\Http\Controllers\Admin\AdminRushPayPaymentController::class, 'checkout'])->name('personal-dues.rushpay.checkout');
    Route::get('personal-dues/rushpay/callback', [\App\Http\Controllers\Admin\AdminRushPayPaymentController::class, 'callback'])->name('personal-dues.rushpay.callback');

    Route::post('course-registrations/bulk', [AdminCourseRegistrationController::class, 'bulk'])->name('course-registrations.bulk');
    Route::resource('course-registrations', AdminCourseRegistrationController::class)->only(['index', 'show', 'update']);
    Route::post('suggestions/bulk', [AdminSuggestionController::class, 'bulk'])->name('suggestions.bulk');
    Route::resource('suggestions', AdminSuggestionController::class)->only(['index', 'show', 'update']);
    Route::get('profile', [AdminProfileController::class, 'index'])->name('profile');
    Route::put('profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::post('profile/admins', [AdminProfileController::class, 'storeAdmin'])->name('profile.admins.store');
    Route::post('profile/snapshots', [AdminProfileController::class, 'createSnapshot'])->name('profile.snapshots.store');
    Route::get('profile/snapshots/download/{snapshot}', [AdminProfileController::class, 'downloadSnapshot'])->name('profile.snapshots.download');

    Route::get('students/export', [\App\Http\Controllers\Admin\AdminStudentAccountController::class, 'export'])
        ->name('students.export');
    Route::post('students/promote-years', [\App\Http\Controllers\Admin\AdminStudentAccountController::class, 'promoteYears'])
        ->name('students.promote-years');
    
    // Pending Registrations Management
    Route::get('pending-registrations', [\App\Http\Controllers\Admin\AdminPendingRegistrationController::class, 'index'])
        ->name('pending-registrations.index');
    Route::get('pending-registrations/{registration}', [\App\Http\Controllers\Admin\AdminPendingRegistrationController::class, 'show'])
        ->name('pending-registrations.show');
    Route::post('pending-registrations/{registration}/approve', [\App\Http\Controllers\Admin\AdminPendingRegistrationController::class, 'approve'])
        ->name('pending-registrations.approve');
    Route::post('/pending-registrations/{registration}/reject', [\App\Http\Controllers\Admin\AdminPendingRegistrationController::class, 'reject'])
        ->name('pending-registrations.reject');
    Route::get('/pending-registrations/{registration}/document', [\App\Http\Controllers\Admin\AdminPendingRegistrationController::class, 'viewDocument'])
        ->name('pending-registrations.view-document');
    
    // Bulk actions for pending registrations
    Route::post('pending-registrations/bulk-approve', [\App\Http\Controllers\Admin\AdminPendingRegistrationController::class, 'bulkApprove'])
        ->name('pending-registrations.bulk-approve');
    Route::post('pending-registrations/bulk-reject', [\App\Http\Controllers\Admin\AdminPendingRegistrationController::class, 'bulkReject'])
        ->name('pending-registrations.bulk-reject');
    Route::delete('pending-registrations/bulk-delete', [\App\Http\Controllers\Admin\AdminPendingRegistrationController::class, 'bulkDelete'])
        ->name('pending-registrations.bulk-delete');
    
    // Live polling API for pending registrations (returns JSON)
    Route::get('api/pending-registrations/count', [\App\Http\Controllers\Admin\AdminPendingRegistrationController::class, 'pendingRegistrationsApi'])
        ->name('api.pending-registrations.count');
    
    Route::resource('students', \App\Http\Controllers\Admin\AdminStudentAccountController::class);

    // Developer Maintenance Routes (Hidden from UI)
    Route::prefix('maintenance')->name('dues.maintenance.')->middleware('admin.role:financial_secretary,president')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'index'])->name('index');
        Route::get('/details', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'showDueDetails'])->name('details');
        Route::post('/sync-missing', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'syncMissing'])->name('sync-missing');
        Route::post('/sync-all', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'syncAll'])->name('sync-all');
        Route::post('/delete-duplicate', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'deleteDuplicate'])->name('delete-duplicate');
        Route::post('/delete-all-duplicates', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'deleteAllDuplicates'])->name('delete-all-duplicates');
        Route::put('/{due}', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'editDue'])->name('edit');
        Route::get('/merge', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'showMerge'])->name('merge-form');
        Route::post('/merge', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'mergeDues'])->name('merge');
        Route::post('/delete-all-orphaned', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'deleteAllOrphaned'])->name('delete-all-orphaned');
        Route::get('/edit-amounts', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'showEditAmounts'])->name('edit-amounts');
        Route::post('/update-single-amount', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'updateSingleAmount'])->name('update-single-amount');
        Route::post('/update-all-amounts', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'updateAllAmounts'])->name('update-all-amounts');
        Route::get('/trace', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'traceStudent'])->name('trace');
        Route::post('/normalize-descriptions', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'normalizeDescriptions'])->name('normalize-descriptions');
        Route::get('/registry', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'studentRegistry'])->name('registry');
        
        // Dues Config Manager
        Route::get('/config', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'duesConfig'])->name('config');
        Route::post('/config/update', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'updateDuesConfig'])->name('config.update');
        Route::post('/config/bulk-update', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'bulkUpdateDuesConfig'])->name('config.bulk-update');
        Route::post('/resync-from-config', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'resyncDuesFromConfig'])->name('resync-from-config');
        Route::post('/bulk-edit-individual', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'bulkEditIndividualDues'])->name('bulk-edit-individual');

        // Account Management (delete accounts, force approve, etc.)
        Route::get('/accounts', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'accountManagement'])->name('accounts');
        Route::delete('/accounts/pending/{id}', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'deletePendingRegistration'])->name('accounts.delete-pending');
        Route::delete('/accounts/user/{id}', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'deleteUserAccount'])->name('accounts.delete-user');
        Route::post('/accounts/pending/{id}/force-approve', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'forceApprovePending'])->name('accounts.force-approve');
        Route::post('/accounts/pending/{id}/force-verify', [\App\Http\Controllers\Admin\AdminDuesMaintenanceController::class, 'forceVerifyEmail'])->name('accounts.force-verify');
    });

});

// SECURED: System optimize route now requires admin authentication.
Route::get('/system-optimize', function() {
    if (app()->environment('production')) {
        abort(404);
    }
    try {
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        return "System optimization complete: Views, Config, Cache, and Routes cleared.";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
})->middleware('auth:admin');