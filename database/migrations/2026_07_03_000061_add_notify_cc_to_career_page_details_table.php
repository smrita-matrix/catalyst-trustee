<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Extra addresses copied on every new job application. */
    public function up(): void
    {
        Schema::table('career_page_details', function (Blueprint $table) {
            $table->string('notify_cc')->nullable()->after('notify_email');
        });
    }

    public function down(): void
    {
        Schema::table('career_page_details', function (Blueprint $table) {
            $table->dropColumn('notify_cc');
        });
    }
};
