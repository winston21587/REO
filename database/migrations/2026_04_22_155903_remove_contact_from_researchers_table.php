<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveContactFromResearchersTable extends Migration
{
    public function up()
    {
        Schema::table('researchers', function (Blueprint $table) {
            $table->dropColumn('contact');
        });
    }

    public function down()
    {
        Schema::table('researchers', function (Blueprint $table) {
            $table->string('contact')->nullable();
        });
    }
}
