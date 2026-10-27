<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A notice can open a page of its own instead of a single document.
 *
 * The default cases each carry a status report, orders and a run of press
 * releases - too much for one card - so the card leads to a page on this
 * site with a banner and the documents listed under their headings.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->tinyInteger('has_detail_page')->default(0)->after('document_link');
            $table->string('detail_slug')->nullable()->after('has_detail_page');
            $table->string('detail_banner_title')->nullable()->after('detail_slug');
            $table->string('detail_banner_image')->nullable()->after('detail_banner_title');
        });

        Schema::create('notice_detail_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notice_id')->index();

            // The heading a document sits under; empty for one that stands alone.
            $table->string('group_title')->nullable();
            $table->string('title', 500);
            $table->string('document_file')->nullable();
            $table->string('document_link', 500)->nullable();
            // A document shown on its own in bold, like a page's status report.
            $table->tinyInteger('is_highlight')->default(0);

            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('modified_at')->nullable();
            $table->unsignedBigInteger('modified_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notice_detail_items');

        Schema::table('notices', function (Blueprint $table) {
            $table->dropColumn(['has_detail_page', 'detail_slug', 'detail_banner_title', 'detail_banner_image']);
        });
    }
};
