<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviewer_file_remarks', function (Blueprint $table) {
            if (!Schema::hasColumn('reviewer_file_remarks', 'file_id')) {
                $table->unsignedBigInteger('file_id')->after('reviewer_id')->nullable();
            }
            if (!Schema::hasColumn('reviewer_file_remarks', 'remarks')) {
                $table->text('remarks')->nullable()->after('file_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reviewer_file_remarks', function (Blueprint $table) {
            if (Schema::hasColumn('reviewer_file_remarks', 'file_id')) {
                $table->dropColumn('file_id');
            }
            if (Schema::hasColumn('reviewer_file_remarks', 'remarks')) {
                $table->dropColumn('remarks');
            }
        });
    }
};
