<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A note shown at the foot of a service page.
 *
 * The services we run that SEBI does not regulate have to say so plainly,
 * and the wording is the same on each of them. Keeping it against the
 * service itself means it shows on whichever layout that service uses, and
 * a service that needs no such note simply leaves it empty.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->text('disclaimer')->nullable()->after('layout');
        });
    }

    public function down(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn('disclaimer');
        });
    }
};
