<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Public Notice menu tree. One self-referencing table covers all three levels:
     *   parent_id = null  -> mega-menu column heading  ("Notices & Announcements")
     *   parent_id = col   -> menu link OR flyout heading ("SEBI Compliance by Debenture Trustee")
     *   parent_id = item  -> flyout link                 ("Revision in Credit Ratings")
     *
     * A node with children is a heading; a node without children is a link.
     */
    public function up(): void
    {
        Schema::create('notice_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();   // null = top level column
            $table->string('name');
            $table->string('slug')->nullable();                    // page URL segment
            $table->string('icon')->nullable();                    // column heading icon
            $table->string('link_type')->default('page');          // page | pdf | url | none
            $table->string('layout')->nullable();                  // which page design to render
            $table->string('document_file')->nullable();           // link_type = pdf
            $table->string('external_link')->nullable();           // link_type = url
            $table->string('page_title')->nullable();              // banner heading on the page
            $table->text('page_intro')->nullable();                // optional paragraph under the heading
            $table->string('alert_heading')->nullable();           // "Attention Investors!" callout box
            $table->text('alert_text')->nullable();                // callout body (HTML allowed)
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);             // 1 = show, 0 = hide

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('modified_at')->nullable();
            $table->unsignedBigInteger('modified_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->index('parent_id');
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notice_categories');
    }
};
