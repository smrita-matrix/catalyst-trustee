<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Each Public Notice page gets its own banner. When these are left empty the
     * page falls back to the shared banner in notice_banner_details, so nothing
     * breaks for pages the admin has not customised.
     */
    public function up(): void
    {
        Schema::table('notice_categories', function (Blueprint $table) {
            $table->string('banner_image')->nullable()->after('page_intro');
            $table->string('banner_title')->nullable()->after('banner_image');
        });
    }

    public function down(): void
    {
        Schema::table('notice_categories', function (Blueprint $table) {
            $table->dropColumn(['banner_image', 'banner_title']);
        });
    }
};
