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
        Schema::dropIfExists('submission_feedbacks');
        Schema::create('submission_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_title_id')->constrained('research_title_information')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type'); // 'admin_deficiency', 'user_correction'
            $table->text('message')->nullable();
            $table->json('missing_requirements')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_feedbacks');
    }
};
