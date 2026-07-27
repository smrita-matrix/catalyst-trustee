<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_layout2_details', function (Blueprint $table) {
            $table->longText('keyfacts_note')->nullable()->after('keyfacts_points');
        });
    }

    public function down(): void
    {
        Schema::table('service_layout2_details', function (Blueprint $table) {
            $table->dropColumn('keyfacts_note');
        });
    }
};
