<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Where new grievance notifications are sent. Editable from the admin. */
    public function up(): void
    {
        Schema::table('grievance_page_details', function (Blueprint $table) {
            $table->string('notify_email')->nullable()->after('support_pdf');
        });
    }

    public function down(): void
    {
        Schema::table('grievance_page_details', function (Blueprint $table) {
            $table->dropColumn('notify_email');
        });
    }
};
