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
        Schema::create('revision_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('research_title_id'); // Using BigInt as per standard, ensure matches parent
            $table->unsignedBigInteger('user_id');
            $table->text('message')->nullable();
            $table->timestamps();

            // Foreign Keys (Assuming standard table names, correct if different)
            // Note: Current migration assumes 'research_title_information' is the table name for Research_title model
            // However, based on controller usage 'Research_title', let's check table name.
            // Earlier migrations show '2025_10_20_141028_create_research_title_information.php'
            $table->foreign('research_title_id')->references('id')->on('research_title_information')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revision_logs');
    }
};
