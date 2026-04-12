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
        \Illuminate\Support\Facades\DB::table('research_title_information')
            ->where('Status', 'Corrections Submitted')
            ->update(['Status' => 'Revision Submitted']);

        \Illuminate\Support\Facades\DB::table('research_title_information')
            ->where('Status', 'Checking of Revisions')
            ->update(['Status' => 'Reviewing Revisions']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::table('research_title_information')
            ->where('Status', 'Revision Submitted')
            ->update(['Status' => 'Corrections Submitted']);  // Note: Data loss possible here if normal Revision Submitted existed before

        \Illuminate\Support\Facades\DB::table('research_title_information')
            ->where('Status', 'Reviewing Revisions')
            ->update(['Status' => 'Checking of Revisions']);
    }
};
