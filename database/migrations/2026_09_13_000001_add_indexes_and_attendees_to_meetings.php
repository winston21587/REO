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
        // 1. Add foreign key and index to agenda_items.protocol_id
        Schema::table('agenda_items', function (Blueprint $table) {
            $table->index('protocol_id');
            $table->foreign('protocol_id')
                ->references('id')
                ->on('research_title_information')
                ->nullOnDelete();
        });

        // 2. Create meeting_attendees table for SOP 17/19 attendance & quorum tracking
        Schema::create('meeting_attendees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained('meetings')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role')->default('reviewer'); // reviewer, chair, secretariat, consultant
            $table->string('status')->default('Invited'); // Invited, Confirmed, Regrets, Attended
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['meeting_id', 'user_id']);
            $table->index(['meeting_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_attendees');

        Schema::table('agenda_items', function (Blueprint $table) {
            $table->dropForeign(['protocol_id']);
            $table->dropIndex(['protocol_id']);
        });
    }
};
