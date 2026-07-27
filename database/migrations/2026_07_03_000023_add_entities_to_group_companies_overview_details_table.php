<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds the Group Companies entity cards (CTL Trusteeship, CTL Fund Services, ...).
     */
    public function up(): void
    {
        Schema::table('group_companies_overview_details', function (Blueprint $table) {
            // JSON array of { image, title, description, link }
            $table->longText('entities')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('group_companies_overview_details', function (Blueprint $table) {
            $table->dropColumn('entities');
        });
    }
};
