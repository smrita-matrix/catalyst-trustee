<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Notices used to be pinned to a hard-coded `section` (bomsc | boc | auc).
     * They now belong to a notice_categories row so new pages can be added
     * from the admin without a code change. `section` is kept as a fallback.
     */
    public function up(): void
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->unsignedBigInteger('notice_category_id')->nullable()->after('id');
            $table->index('notice_category_id');
        });

        // Existing rows are linked to their category by NoticeCategorySeeder,
        // which runs after this migration and is what creates those categories.
    }

    public function down(): void
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->dropIndex(['notice_category_id']);
            $table->dropColumn('notice_category_id');
        });
    }
};
