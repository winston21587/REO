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
        Schema::create('title_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_title_id')->constrained('research_title_information')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action'); // e.g. 'Status Changed', 'Title Updated', 'Submitted'
            $table->text('description')->nullable(); // Detailed info like: "Status changed from Pending to Received"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('title_logs');
    }
};
