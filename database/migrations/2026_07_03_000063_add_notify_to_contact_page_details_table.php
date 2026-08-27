<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Where enquiry notifications are sent. */
    public function up(): void
    {
        Schema::table('contact_page_details', function (Blueprint $table) {
            $table->string('notify_email')->nullable();
            $table->string('notify_cc')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('contact_page_details', function (Blueprint $table) {
            $table->dropColumn(['notify_email', 'notify_cc']);
        });
    }
};
