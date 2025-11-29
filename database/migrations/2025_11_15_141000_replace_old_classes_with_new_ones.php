<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Replace all legacy class names with a new one
        // Since mapping does not matter, we normalize everything to
        // "Geomatic Engineering".
        DB::statement("UPDATE `users` SET `class` = 'Geomatic Engineering' WHERE `class` IN ('Cyber Security','Computer Science','Information System')");

        // 2) Restrict the enum to only the three new classes
        DB::statement("ALTER TABLE `users` MODIFY `class` ENUM(
            'Geomatic Engineering',
            'Land Administration',
            'Spatial Planning'
        ) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL");
    }

    public function down(): void
    {
        // This down() restores the wider enum so older values would be valid again
        // (it does NOT try to restore the previous per-row values).
        DB::statement("ALTER TABLE `users` MODIFY `class` ENUM(
            'Cyber Security',
            'Computer Science',
            'Information System',
            'Geomatic Engineering',
            'Land Administration',
            'Spatial Planning'
        ) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL");
    }
};
