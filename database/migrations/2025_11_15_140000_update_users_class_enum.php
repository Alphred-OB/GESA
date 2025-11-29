<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Extend the users.class enum to include the new GESA programs
        DB::statement("ALTER TABLE `users` MODIFY `class` ENUM(
            'Cyber Security',
            'Computer Science',
            'Information System',
            'Geomatic Engineering',
            'Land Administration',
            'Spatial Planning'
        ) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL");
    }

    public function down(): void
    {
        // Revert back to the original enum. NOTE: this will fail if rows
        // still contain any of the new class names.
        DB::statement("ALTER TABLE `users` MODIFY `class` ENUM(
            'Cyber Security',
            'Computer Science',
            'Information System'
        ) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL");
    }
};
