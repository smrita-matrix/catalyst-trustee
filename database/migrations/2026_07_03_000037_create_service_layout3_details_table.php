<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_layout3_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();

            // Banner
            $table->string('banner_breadcrumb_parent')->nullable();
            $table->string('banner_breadcrumb_child')->nullable();
            $table->string('banner_background_image')->nullable();

            // Intro
            $table->string('intro_image')->nullable();
            $table->text('intro_heading')->nullable();
            $table->longText('intro_description')->nullable();

            // Services (vertical tabs)
            $table->text('services_divider_label')->nullable();
            $table->longText('services_tabs')->nullable();   // JSON [{ icon, title, description, points }]

            // Key Benefits
            $table->string('benefits_image')->nullable();
            $table->text('benefits_heading')->nullable();
            $table->longText('benefits_points')->nullable();
            $table->longText('benefits_note')->nullable();

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
        Schema::dropIfExists('service_layout3_details');
    }
};
