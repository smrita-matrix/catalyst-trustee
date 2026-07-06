<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Stores the "add more" feature cards as a JSON array on the About Catalyst record.
     */
    public function up(): void
    {
        Schema::table('about_catalyst_details', function (Blueprint $table) {
            $table->longText('features')->nullable()->after('button_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('about_catalyst_details', function (Blueprint $table) {
            $table->dropColumn('features');
        });
    }
};
