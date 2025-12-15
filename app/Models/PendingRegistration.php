<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingRegistration extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'phone_number',
        'index_number',
        'class',
        'year',
        'password',
        'reason',
        'student_id_path',
        'status',
        'admin_notes',
        'reviewed_at',
        'reviewed_by',
        'verification_code',
        'verification_expires_at',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'verification_expires_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];
}
