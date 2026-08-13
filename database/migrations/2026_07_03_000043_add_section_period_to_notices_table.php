<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->string('section')->default('bomsc')->after('id'); // bomsc | boc | auc
            $table->string('period')->nullable()->after('section');   // e.g. "September 2025" (date pill / month group)
        });
    }

    public function down(): void
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->dropColumn(['section', 'period']);
        });
    }
};
