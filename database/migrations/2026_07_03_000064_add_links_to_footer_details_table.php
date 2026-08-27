<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Site-wide links that were previously hard-coded as href="#":
     * the footer Quick Links list and the header "Get Started" button.
     */
    public function up(): void
    {
        Schema::table('footer_details', function (Blueprint $table) {
            $table->longText('quick_links')->nullable();       // JSON: [{label, url}]
            $table->string('get_started_text')->nullable();
            $table->string('get_started_link')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('footer_details', function (Blueprint $table) {
            $table->dropColumn(['quick_links', 'get_started_text', 'get_started_link']);
        });
    }
};
