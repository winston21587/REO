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
        // Change ENUM to VARCHAR to support dynamic research categories from the Super Admin panel
        DB::statement('ALTER TABLE research_title_information MODIFY funding_type VARCHAR(255) NULL');
        DB::statement('ALTER TABLE research_title_information MODIFY thesis_type VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We do not revert to ENUM safely because data might be lost, but we can set it back if needed
        DB::statement("ALTER TABLE research_title_information MODIFY funding_type ENUM('Institutionally Funded', 'Externally Funded') NULL");
        DB::statement("ALTER TABLE research_title_information MODIFY thesis_type ENUM('Undergraduate thesis', 'Masters thesis', 'Dissertation') NULL");
    }
};
