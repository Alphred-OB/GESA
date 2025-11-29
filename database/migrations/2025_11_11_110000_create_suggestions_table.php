<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('suggestions')) {
            Schema::create('suggestions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users', 'user_id')->cascadeOnDelete();
                $table->string('category');
                $table->string('subject', 160);
                $table->text('message');
                $table->string('attachment_path')->nullable();
                $table->string('status', 40)->default('pending');
                $table->timestamp('handled_at')->nullable();
                $table->timestamps();

                $table->index(['status', 'handled_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('suggestions');
    }
};
