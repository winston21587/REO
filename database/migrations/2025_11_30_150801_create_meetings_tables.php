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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type'); // Regular, Special
            $table->dateTime('meeting_date');
            $table->string('venue')->nullable();
            $table->string('status')->default('Scheduled'); // Scheduled, Completed, Cancelled
            $table->string('agenda_status')->default('Draft'); // Draft, Provisional, Final
            $table->timestamps();
        });

        Schema::create('agenda_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained()->onDelete('cascade');
            $table->string('section'); // Call to Order, New Business, etc.
            $table->text('content');
            $table->integer('order')->default(0);
            $table->foreignId('protocol_id')->nullable(); // Link to a protocol if applicable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_items');
        Schema::dropIfExists('meetings');
    }
};
