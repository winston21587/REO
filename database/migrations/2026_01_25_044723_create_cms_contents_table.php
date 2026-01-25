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
        Schema::create('cms_contents', function (Blueprint $table) {
            $table->id();
            $table->string('section')->index(); // e.g., 'homepage', 'footer'
            $table->string('key')->unique();    // e.g., 'hero_title'
            $table->longText('value')->nullable();
            $table->string('type')->default('text'); // text, image, html
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_contents');
    }
};
