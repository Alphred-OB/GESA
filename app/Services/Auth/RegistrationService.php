<?php

namespace App\Services\Auth;

use App\Mail\Student\RegistrationApprovedMail;
use App\Mail\Student\RegistrationRejectedMail;
use App\Models\PendingRegistration;
use App\Models\User;
use App\Services\Admin\AdminDueService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RegistrationService
{
    public function __construct(private readonly AdminDueService $dueService)
    {}

    /**
     * Approve a pending registration and create the user account.
     * 
     * @throws \RuntimeException
     */
    public function approve(PendingRegistration $registration, ?string $notes = null): User
    {
        return DB::transaction(function () use ($registration, $notes) {
            $this->validateNoConflicts($registration);

            $fullName = trim($registration->first_name . ' ' . $registration->last_name);

            // Create the user account
            $user = new User();
            $user->fullname = $fullName;
            $user->username = $registration->username;
            $user->email = $registration->email;
            $user->phone_number = $registration->phone_number;
            $user->index_number = $registration->index_number;
            $user->class = $registration->class;
            $user->year = $registration->year;
            $user->role = 'student';
            $user->email_verified_at = $registration->email_verified_at;
            
            // Set password directly to avoid double hashing since it's already hashed in PendingRegistration
            $user->forceFill(['password' => $registration->password]);
            $user->save();

            // Sync dues
            $this->dueService->syncStudent($user);

            // Update pending registration
            $registration->update([
                'status' => 'approved',
                'reviewed_at' => now(),
                'reviewed_by' => Auth::guard('admin')->id(),
                'admin_notes' => $notes,
            ]);

            // Send approval email
            Mail::to($user->email)->queue(new RegistrationApprovedMail($user, $registration));

            return $user;
        });
    }

    /**
     * Reject a pending registration.
     */
    public function reject(PendingRegistration $registration, ?string $notes = null): void
    {
        DB::transaction(function () use ($registration, $notes) {
            // If it was already approved, we need to clean up the user account
            if ($registration->status === 'approved') {
                User::where('email', $registration->email)->delete();
            }

            $registration->update([
                'status' => 'rejected',
                'reviewed_at' => now(),
                'reviewed_by' => Auth::guard('admin')->id(),
                'admin_notes' => $notes,
            ]);

            // Send rejection email
            Mail::to($registration->email)->queue(new RegistrationRejectedMail($registration));
        });
    }

    /**
     * Validate that there are no existing users with the same identifiers.
     * 
     * @throws \RuntimeException
     */
    private function validateNoConflicts(PendingRegistration $registration): void
    {
        $conflicts = [];
        
        if (User::where('username', $registration->username)->exists()) {
            $conflicts[] = "username '{$registration->username}'";
        }
        
        if (User::where('email', $registration->email)->exists()) {
            $conflicts[] = "email '{$registration->email}'";
        }
        
        if ($registration->index_number && User::where('index_number', $registration->index_number)->exists()) {
            $conflicts[] = "reference number '{$registration->index_number}'";
        }
        
        if (!empty($conflicts)) {
            throw new \RuntimeException(
                'Cannot approve: A user already exists with ' . implode(' and ', $conflicts) . '. ' .
                'Please reject this registration or ask the student to use a different username.'
            );
        }
    }
}
