<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** News & Media cards shown on the listing page. */
    public function up(): void
    {
        Schema::create('news_media', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->nullable();     // badge text on the image
            $table->string('image')->nullable();
            $table->string('link')->nullable();         // external "Read More" URL
            $table->string('pdf_file')->nullable();     // or an uploaded PDF
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
        Schema::dropIfExists('news_media');
    }
};
