<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Editable content for the Grievance section. One row holds the Investor
     * Grievance page copy plus the PDF the "Contact for Support" menu item opens.
     * The form fields themselves are fixed — only the wording around them is CMS-driven.
     */
    public function up(): void
    {
        Schema::create('grievance_page_details', function (Blueprint $table) {
            $table->id();

            // Banner
            $table->string('banner_title')->nullable();
            $table->string('breadcrumb_child')->nullable();
            $table->string('banner_image')->nullable();

            // Page copy
            $table->text('intro_text')->nullable();              // the line above the form
            $table->string('holder_heading')->nullable();        // "Investor/Debenture Holder Details"
            $table->string('instrument_heading')->nullable();    // "Instrument Details & Grievance"
            $table->longText('complaint_options')->nullable();   // JSON list of checkbox labels
            $table->longText('notes')->nullable();               // JSON list of the footnotes

            // "Contact for Support" menu item — a PDF only
            $table->string('support_pdf')->nullable();

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
        Schema::dropIfExists('grievance_page_details');
    }
};
