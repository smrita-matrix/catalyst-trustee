<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * What a menu entry under Grievance opens.
 *
 * Most of them open a page on the site. Some are simply a document, and
 * building a page around it only puts a second click between the reader and
 * the thing they came for, so those open the document itself in a new tab.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('grievance_pages', function (Blueprint $table) {
            $table->string('link_target', 20)->default('page')->after('form_type');
        });
    }

    public function down(): void
    {
        Schema::table('grievance_pages', function (Blueprint $table) {
            $table->dropColumn('link_target');
        });
    }
};
