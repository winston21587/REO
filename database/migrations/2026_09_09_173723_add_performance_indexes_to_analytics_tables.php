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
            $table->index('created_at', 'idx_rti_created_at');
            $table->index('Status', 'idx_rti_status');
            $table->index('Review_Type', 'idx_rti_review_type');
            $table->index('Research_Category', 'idx_rti_category');
            $table->index(['Status', 'created_at'], 'idx_rti_status_created_at');
        });

        Schema::table('researchers', function (Blueprint $table) {
            $table->index('college', 'idx_researchers_college');
            $table->index('external_user', 'idx_researchers_external_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('research_title_information', function (Blueprint $table) {
            $table->dropIndex('idx_rti_created_at');
            $table->dropIndex('idx_rti_status');
            $table->dropIndex('idx_rti_review_type');
            $table->dropIndex('idx_rti_category');
            $table->dropIndex('idx_rti_status_created_at');
        });

        Schema::table('researchers', function (Blueprint $table) {
            $table->dropIndex('idx_researchers_college');
            $table->dropIndex('idx_researchers_external_user');
        });
    }
};
