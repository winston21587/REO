<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('research_title_information', function (Blueprint $table) {
            $table->string('project_type')->nullable()->after('thesis_type'); // 'Funded Research' or 'Course Requirement'
            $table->string('course_type')->nullable()->after('project_type');  // 'Undergraduate Thesis', 'MA (Graduate Thesis)', 'Dissertation'
        });
    }

    public function down(): void
    {
        Schema::table('research_title_information', function (Blueprint $table) {
            $table->dropColumn(['project_type', 'course_type']);
        });
    }
};
