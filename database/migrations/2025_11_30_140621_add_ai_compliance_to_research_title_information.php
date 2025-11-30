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
            if (!Schema::hasColumn('research_title_information', 'ai_score')) {
                $table->integer('ai_score')->default(0)->after('Status'); // 0-100%
            }
            if (!Schema::hasColumn('research_title_information', 'is_human_verified')) {
                $table->boolean('is_human_verified')->default(false)->after('ai_score');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('research_title_information', function (Blueprint $table) {
            $table->dropColumn(['ai_score', 'is_human_verified']);
        });
    }
};
