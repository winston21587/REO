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
        Schema::table('reviewer_file_remarks', function (Blueprint $table) {
            if (!Schema::hasColumn('reviewer_file_remarks', 'research_title_id')) {
                $table->unsignedBigInteger('research_title_id')->nullable()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviewer_file_remarks', function (Blueprint $table) {
            if (Schema::hasColumn('reviewer_file_remarks', 'research_title_id')) {
                $table->dropColumn('research_title_id');
            }
        });
    }
};
