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
        Schema::create('research_title_information', function (Blueprint $table) {
            $table->id();
            $table->string('Study_Protocol_title');
            $table->string('Research_Category');
            $table->string('Review_Type')->default('N/A');
            $table->string('Created_by')->nullable();
            $table->string('Status')->default('Pending');
            $table->string('Adviser')->nullable();
            $table->integer('Official_Receipt_Number')->default(null);
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // $table->foreignId('researcher_file_id')
            //       ->constrained('researcher_files')
            //       ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_title_information');
    }
};
