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
            // Add thesis type column
            $table->enum('thesis_type', [
                'Undergraduate thesis',
                'Masters thesis',
                'Dissertation'
            ])->nullable()->after('Review_Type');

            // Add funding type column
            $table->enum('funding_type', [
                'Institutionally Funded',
                'Externally Funded'
            ])->nullable()->after('thesis_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('research_title_information', function (Blueprint $table) {
            $table->dropColumn(['thesis_type', 'funding_type']);
        });
    }
};
