<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_fif_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();

            // Banner
            $table->string('banner_breadcrumb_parent')->nullable();
            $table->string('banner_breadcrumb_child')->nullable();
            $table->string('banner_background_image')->nullable();

            // Intro
            $table->string('intro_image')->nullable();
            $table->text('intro_subheading')->nullable();
            $table->longText('intro_description')->nullable();

            // Definition / Concept
            $table->string('definition_image')->nullable();
            $table->longText('definition_cards')->nullable();     // JSON [{ heading, content }]

            // Process (tabs)
            $table->text('process_heading')->nullable();
            $table->longText('process_tabs')->nullable();          // JSON [{ title, description, points }]

            // Tax comparison
            $table->longText('tax_intro')->nullable();
            $table->longText('tax_table_html')->nullable();        // raw HTML table

            // Family Office Solution
            $table->text('family_heading')->nullable();
            $table->longText('family_description')->nullable();
            $table->string('family_image')->nullable();

            // Capabilities
            $table->string('capabilities_image')->nullable();
            $table->text('capabilities_heading')->nullable();
            $table->longText('capabilities_points')->nullable();
            $table->longText('capabilities_disclaimer')->nullable();

            // Audit
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
        Schema::dropIfExists('service_fif_details');
    }
};
