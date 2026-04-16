<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('title_reviewer_assignments', function (Blueprint $table) {
            $table->json('reviewer_remarks')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('title_reviewer_assignments', function (Blueprint $table) {
            $table->dropColumn('reviewer_remarks');
        });
    }
};
