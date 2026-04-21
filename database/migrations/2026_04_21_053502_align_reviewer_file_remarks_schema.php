<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reviewer_file_remarks', function (Blueprint $table) {
            // Fix reviewer_id vs user_id
            if (!Schema::hasColumn('reviewer_file_remarks', 'reviewer_id')) {
                if (Schema::hasColumn('reviewer_file_remarks', 'user_id')) {
                    $table->renameColumn('user_id', 'reviewer_id');
                } else {
                    $table->unsignedBigInteger('reviewer_id')->nullable();
                    // Optional: $table->foreign('reviewer_id')->references('id')->on('users')->onDelete('cascade');
                }
            }

            // Fix file_id vs researcher_file_id
            if (!Schema::hasColumn('reviewer_file_remarks', 'file_id')) {
                if (Schema::hasColumn('reviewer_file_remarks', 'researcher_file_id')) {
                    $table->renameColumn('researcher_file_id', 'file_id');
                } else {
                    $table->unsignedBigInteger('file_id')->nullable();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviewer_file_remarks', function (Blueprint $table) {
            if (Schema::hasColumn('reviewer_file_remarks', 'reviewer_id') && !Schema::hasColumn('reviewer_file_remarks', 'user_id')) {
                $table->renameColumn('reviewer_id', 'user_id');
            }
            if (Schema::hasColumn('reviewer_file_remarks', 'file_id') && !Schema::hasColumn('reviewer_file_remarks', 'researcher_file_id')) {
                $table->renameColumn('file_id', 'researcher_file_id');
            }
        });
    }
};
