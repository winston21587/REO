<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
        {
            Schema::create('user_notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Who gets the notification
                $table->foreignId('research_id')->nullable()->constrained('research_title_information')->onDelete('cascade'); // Related research
                $table->string('title'); // e.g., "Status Update"
                $table->text('message'); // e.g., "Your research status is now..."
                $table->string('type')->default('info'); // info, warning, success
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
    }
};
