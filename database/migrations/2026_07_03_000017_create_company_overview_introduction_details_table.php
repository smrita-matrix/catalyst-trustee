<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('company_overview_introduction_details', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('experience_number')->nullable();  // e.g. 29+
            $table->string('experience_label')->nullable();   // e.g. Years Of Fiduciary & Trusteeship Expertise
            $table->string('established_label')->nullable();   // e.g. Established In
            $table->string('established_year')->nullable();    // e.g. 1997
            $table->string('sub_heading')->nullable();         // e.g. Company Overview
            $table->text('heading')->nullable();               // e.g. Introduction to Catalyst Trusteeship Limited
            $table->text('tagline')->nullable();               // e.g. India's Trusted Partner in Trusteeship & Fiduciary Solutions
            $table->longText('description')->nullable();       // main paragraphs (rich text)
            $table->longText('more_content')->nullable();      // expandable "Read More" paragraphs (rich text)
            $table->string('button_text')->nullable();
            $table->string('button_link')->nullable();

            // Custom audit columns (model has $timestamps = false)
            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('modified_at')->nullable();
            $table->unsignedBigInteger('modified_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_overview_introduction_details');
    }
};
