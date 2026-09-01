<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Room for two pieces of supplied copy that had nowhere to live.
     *
     * A News & Media entry was only a headline and a link, so the write-up that
     * comes with each item could not be shown. "Life at Catalyst" was a single
     * heading and paragraph, but the copy for it is a set of separate stories,
     * each with its own title, text and photo album link.
     */
    public function up(): void
    {
        Schema::table('news_media', function (Blueprint $table) {
            $table->text('description')->nullable()->after('title');
        });

        Schema::table('career_page_details', function (Blueprint $table) {
            // JSON list of {title, text, link}
            $table->longText('life_stories')->nullable()->after('intro_text');
        });
    }

    public function down(): void
    {
        Schema::table('news_media', function (Blueprint $table) {
            $table->dropColumn('description');
        });

        Schema::table('career_page_details', function (Blueprint $table) {
            $table->dropColumn('life_stories');
        });
    }
};
