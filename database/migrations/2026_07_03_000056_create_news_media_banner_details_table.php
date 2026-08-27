<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Banner for the News & Media listing page. */
    public function up(): void
    {
        Schema::create('news_media_banner_details', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('breadcrumb_parent')->nullable();
            $table->string('breadcrumb_child')->nullable();
            $table->string('background_image')->nullable();
            $table->string('section_heading')->nullable(); // heading above the cards

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
        Schema::dropIfExists('news_media_banner_details');
    }
};
