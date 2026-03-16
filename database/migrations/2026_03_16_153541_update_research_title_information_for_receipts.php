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
        Schema::table('research_title_information', function (Blueprint $table) {
            $table->string('Official_Receipt_Number')->nullable()->change();
            $table->string('or_file_path')->nullable()->after('Official_Receipt_Number');
            $table->boolean('is_or_verified')->default(false)->after('or_file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('research_title_information', function (Blueprint $table) {
            $table->dropColumn(['or_file_path', 'is_or_verified']);
            $table->string('Official_Receipt_Number')->nullable(false)->change();
        });
    }
};
