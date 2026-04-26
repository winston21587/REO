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
        Schema::create('submission_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('researcher_id')->constrained('researchers')->onDelete('cascade');
            $table->string('status'); // 'success', 'failed', 'blocked'
            $table->integer('response_code')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            // Indexes for efficient querying
            $table->index(['researcher_id', 'created_at']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_attempts');
    }
};
