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
        Schema::table('events', function (Blueprint $table) {
            $table->string('type')->default('physical')->after('location')->comment('physical, online, hybrid');
            $table->string('meeting_link')->nullable()->after('type');
            $table->string('meeting_passcode')->nullable()->after('meeting_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['type', 'meeting_link', 'meeting_passcode']);
        });
    }
};
