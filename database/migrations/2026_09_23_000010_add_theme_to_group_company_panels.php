<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lets the Group Companies page carry more than one company panel.
 *
 * The page was built around a single panel - Catalyst (DIFC) - on a dark grey
 * ground. Monarch in Mauritius is the same shape of panel in the approved
 * design, only terracotta, so a panel now says which of the two it wears and
 * where it sits on the page.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('group_companies_difc_details', function (Blueprint $table) {
            $table->string('theme', 20)->default('dark')->after('button_link');
            $table->integer('sort_order')->default(0)->after('theme');
        });
    }

    public function down(): void
    {
        Schema::table('group_companies_difc_details', function (Blueprint $table) {
            $table->dropColumn(['theme', 'sort_order']);
        });
    }
};
