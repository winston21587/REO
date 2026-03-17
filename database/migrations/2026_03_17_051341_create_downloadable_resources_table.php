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
        Schema::create('downloadable_resources', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable(); // e.g., FR.002
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('file_size')->nullable(); // e.g., 154 KB
            $table->string('file_extension')->nullable(); // e.g., DOCX
            $table->boolean('is_mandatory')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downloadable_resources');
    }
};
