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
            $table->foreignId('research_title_id')->constrained('research_title_information')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('researcher_files', function (Blueprint $table) {
            $table->dropForeign(['research_title_id']);
            $table->dropColumn('research_title_id');
        });
    }
};
