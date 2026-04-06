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
        Schema::table('researcher_files', function (Blueprint $table) {
            $table->unsignedBigInteger('uploaded_by')->nullable()->after('research_title_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('researcher_files', function (Blueprint $table) {
            $table->dropColumn('uploaded_by');
        });
    }
};
