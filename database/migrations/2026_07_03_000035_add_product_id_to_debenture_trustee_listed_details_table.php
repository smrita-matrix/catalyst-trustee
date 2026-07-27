<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('debenture_trustee_listed_details', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->after('category_id');
        });
    }

    public function down(): void
    {
        Schema::table('debenture_trustee_listed_details', function (Blueprint $table) {
            $table->dropColumn('product_id');
        });
    }
};
