<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
Schema::create('research_title_files', function (Blueprint $table) {
    $table->id();
    $table->foreignId('research_title_id')->constrained('research_title_information')->onDelete('cascade');
    $table->foreignId('researcher_file_id')->constrained('researcher_files')->onDelete('cascade');
    $table->timestamps();
});


    }

    public function down(): void
    {
        Schema::dropIfExists('research_title_files');
    }
};
