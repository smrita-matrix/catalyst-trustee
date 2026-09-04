<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Addresses copied on the notification sent to the grievance officer. */
    public function up(): void
    {
        Schema::table('grievance_page_details', function (Blueprint $table) {
            $table->string('notify_cc', 500)->nullable()->after('notify_email');
        });
    }

    public function down(): void
    {
        Schema::table('grievance_page_details', function (Blueprint $table) {
            $table->dropColumn('notify_cc');
        });
    }
};
