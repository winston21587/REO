<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('research_title_information', function (Blueprint $table) {
            $table->boolean('is_cv_verified')->default(false)->after('is_or_verified');
            $table->string('cv_verification_status')->nullable()->after('is_cv_verified'); // 'Valid', 'Invalid', or null
            $table->text('cv_rejection_remarks')->nullable()->after('cv_verification_status');
        });
    }

    public function down(): void
    {
        Schema::table('research_title_information', function (Blueprint $table) {
            $table->dropColumn(['is_cv_verified', 'cv_verification_status', 'cv_rejection_remarks']);
        });
    }
};
