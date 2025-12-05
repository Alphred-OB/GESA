<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pending_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username')->unique();
            $table->string('email'); // Personal email
            $table->string('phone_number')->nullable();
            $table->string('index_number')->unique();
            $table->enum('class', ['Geomatic Engineering', 'Land Administration', 'Spatial Planning']);
            $table->enum('year', ['1', '2', '3', '4']);
            $table->string('password'); // Hashed password
            $table->text('reason'); // Why they can't access student email
            $table->string('student_id_path')->nullable(); // Path to uploaded student ID
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_registrations');
    }
};
