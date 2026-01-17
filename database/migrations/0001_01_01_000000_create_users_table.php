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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->string('role')->default('researcher'); // researcher, admin, super_admin
            
            $table->boolean('is_verified')->default(false);
            $table->string('verification_code')->nullable();
            
            $table->json('email_preferences')->nullable();
            $table->json('display_preferences')->nullable();
            $table->boolean('first_time')->default(false);

            $table->rememberToken();
            $table->string('reset_code')->nullable();
            $table->timestamp('reset_code_expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('researchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('college')->nullable();
            $table->string('department')->nullable();
            $table->string('institute')->nullable();
            $table->string('course')->nullable();
            $table->boolean('external_user')->default(false);
            $table->string('contact')->nullable();
        
            $table->json('expertise')->nullable();
            $table->boolean('training_completed')->default(false);
            
            $table->timestamps();
        });

        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('member_type')->nullable(); // Scientist or Non-Scientist
            $table->string('position')->nullable(); // Chair, Vice-Chair, Secretary, Member
            
            $table->string('college')->nullable();
            $table->json('expertise')->nullable();
            $table->boolean('training_completed')->default(false);
            $table->boolean('external_user')->default(false); //might indicate its an external admin? maybe

            $table->timestamps();
        });

        Schema::create('super_admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('super_admins');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('researchers');
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
