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
        Schema::table('default_dues_config', function (Blueprint $table) {
            // Drop the old unique index if it exists
            $table->dropUnique('class_year_unique');
            
            // Add the new unique index including description
            $table->unique(['class', 'year', 'description'], 'class_year_desc_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('default_dues_config', function (Blueprint $table) {
            $table->dropUnique('class_year_desc_unique');
            $table->unique(['class', 'year'], 'class_year_unique');
        });
    }
};
