<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_title_id')->constrained('research_title_information')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // researcher or student
            $table->string('stage'); // e.g., 'Initial Review', 'Revision', 'Final Review'
            $table->date('appointment_date');
            $table->text('notes')->nullable(); // optional remarks or agenda
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
