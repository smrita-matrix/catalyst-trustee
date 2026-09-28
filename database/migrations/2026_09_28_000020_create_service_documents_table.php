<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Documents shown on a service page.
 *
 * Some services are not a description of work but a place to put papers -
 * GIFT City's Policy page is the first of them. Rather than borrowing the
 * Public Notice machinery, which would put the page at the wrong address and
 * under the wrong heading, a service can simply hold its own documents.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('document_file')->nullable();
            $table->string('document_link', 500)->nullable();

            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('modified_at')->nullable();
            $table->unsignedBigInteger('modified_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        /* The page around them: its banner and the line under the heading. */
        Schema::create('service_document_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();

            $table->string('banner_title')->nullable();
            $table->string('banner_breadcrumb_parent')->nullable();
            $table->string('banner_breadcrumb_child')->nullable();
            $table->string('banner_background_image')->nullable();

            $table->string('page_title')->nullable();
            $table->text('page_intro')->nullable();

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
        Schema::dropIfExists('service_documents');
        Schema::dropIfExists('service_document_pages');
    }
};
