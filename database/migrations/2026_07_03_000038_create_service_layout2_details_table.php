<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_layout2_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();

            // Banner
            $table->string('banner_breadcrumb_parent')->nullable();
            $table->string('banner_breadcrumb_child')->nullable();
            $table->string('banner_background_image')->nullable();

            // Nature Of Work
            $table->string('nature_image')->nullable();
            $table->text('nature_heading')->nullable();
            $table->longText('nature_description')->nullable();

            // Process & Execution
            $table->string('process_image')->nullable();
            $table->text('process_heading')->nullable();
            $table->longText('process_points')->nullable();

            // Key Facts
            $table->string('keyfacts_image')->nullable();
            $table->text('keyfacts_heading')->nullable();
            $table->longText('keyfacts_points')->nullable();

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
        Schema::dropIfExists('service_layout2_details');
    }
};
