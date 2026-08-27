<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Editable content for the Careers page, plus where applications are emailed. */
    public function up(): void
    {
        Schema::create('career_page_details', function (Blueprint $table) {
            $table->id();

            // Banner
            $table->string('banner_title')->nullable();
            $table->string('breadcrumb_child')->nullable();
            $table->string('banner_image')->nullable();

            // Intro block above the openings
            $table->text('intro_heading')->nullable();
            $table->text('intro_text')->nullable();

            // Form block
            $table->string('form_sub_heading')->nullable();  // "Apply Now"
            $table->string('form_heading')->nullable();      // "Submit Your Resume"

            // Where new applications are emailed
            $table->string('notify_email')->nullable();

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
        Schema::dropIfExists('career_page_details');
    }
};
