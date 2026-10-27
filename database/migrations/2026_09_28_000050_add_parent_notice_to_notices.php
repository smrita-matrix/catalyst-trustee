<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A notice's page can hold pages of its own: DHFL's page leads to "DHFL IBC",
 * "Status Report DHFL" and so on, which were separate pages on the old site.
 * Such a page is a notice that belongs to another notice instead of showing
 * as a card on the list.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_notice_id')->nullable()->index()->after('notice_category_id');
        });
    }

    public function down(): void
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->dropColumn('parent_notice_id');
        });
    }
};
