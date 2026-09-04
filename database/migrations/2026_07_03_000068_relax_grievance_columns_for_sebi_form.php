<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The SEBI form asks for less than the other one - no postal address, no
     * bond count, and the complaint is written out rather than ticked from a
     * list. Those columns were required because only one form existed when the
     * table was made, so a SEBI submission could not be saved at all.
     *
     * Each form still enforces its own required fields; this only stops the
     * table demanding columns that one of the two forms never asks for.
     */
    public function up(): void
    {
        Schema::table('grievances', function (Blueprint $table) {
            $table->text('address')->nullable()->change();
            $table->string('bonds_held')->nullable()->change();
            $table->text('complaint_details')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('grievances', function (Blueprint $table) {
            $table->text('address')->nullable(false)->change();
            $table->string('bonds_held')->nullable(false)->change();
            $table->text('complaint_details')->nullable(false)->change();
        });
    }
};
