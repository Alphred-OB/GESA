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
        ->name('auth.login.submit');

    Route::get('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\Auth\NewPasswordController::class, 'store'])
        ->name('password.update');

    Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'create'])
        ->name('auth.register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'store'])
        ->name('auth.register.submit');

    // EMERGENCY CACHE CLEARER - Visit this URL once, then DELETE this route!
    Route::get('/emergency-clear-cache-now', function() {
        try {
            Artisan::call('route:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            
            return response()->json([
                'status' => 'success',
                'message' => 'All caches cleared successfully! OTP verification is now active.',
                'next_steps' => [
                    '1. Test fresher registration - it should now require OTP',
                    '2. DELETE this /emergency-clear-cache-now route from routes/web.php',
                    '3. Redeploy without this route'
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    });

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

    Route::get('dues/payment-settings', [\App\Http\Controllers\Admin\AdminPaymentSettingController::class, 'index'])
        ->name('payment-settings.index')
        ->middleware('admin.role:financial_secretary');
    Route::put('dues/payment-settings', [\App\Http\Controllers\Admin\AdminPaymentSettingController::class, 'update'])
        ->name('payment-settings.update')
        ->middleware('admin.role:financial_secretary');

    Route::get('dues/{due}/verify', [AdminDueController::class, 'verify'])
        ->name('dues.verify')
        ->middleware('admin.role:financial_secretary');
    Route::post('dues/{due}/approve', [AdminDueController::class, 'approve'])
        ->name('dues.approve')
        ->middleware('admin.role:financial_secretary');
    Route::post('dues/{due}/reject', [AdminDueController::class, 'reject'])
        ->name('dues.reject')
        ->middleware('admin.role:financial_secretary');

    Route::resource('dues', AdminDueController::class)
        ->except(['show'])
        ->middleware('admin.role:financial_secretary');

    // Admin Personal Dues
    Route::get('personal-dues', [AdminPersonalDueController::class, 'index'])->name('personal-dues.index');
    Route::get('personal-dues/{due}/manual', [AdminPersonalDueController::class, 'showManualPayment'])->name('personal-dues.manual.show');
    Route::post('personal-dues/{due}/manual', [AdminPersonalDueController::class, 'storeManualPayment'])->name('personal-dues.manual.store');

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
    Route::post('pending-registrations/{registration}/reject', [\App\Http\Controllers\Admin\AdminPendingRegistrationController::class, 'reject'])
        ->name('pending-registrations.reject');
    
    Route::resource('students', \App\Http\Controllers\Admin\AdminStudentAccountController::class);

});

// Critical: Secret route to clear caches when terminal access is unavailable
Route::get('/system-optimize', function() {
    try {
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        return "System optimization complete: Views, Config, Cache, and Routes cleared.";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});
