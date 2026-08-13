<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_page_details', function (Blueprint $table) {
            $table->id();
            // Banner
            $table->string('banner_title')->nullable();
            $table->string('banner_breadcrumb_parent')->nullable();
            $table->string('banner_background_image')->nullable();
            // Contact Information (3 cards)
            $table->string('info_heading')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_link')->nullable();
            $table->string('email')->nullable();
            $table->string('email_link')->nullable();
            $table->text('address')->nullable();
            $table->string('address_link')->nullable();
            // Enquiry Form
            $table->string('enquiry_heading')->nullable();
            $table->string('form_heading')->nullable();
            $table->string('form_image')->nullable();
            $table->text('services_options')->nullable();  // one per line
            $table->text('location_options')->nullable();  // one per line
            // Office Locations headings
            $table->string('office_heading')->nullable();
            $table->string('main_office_subtitle')->nullable();
            $table->string('other_office_subtitle')->nullable();
            $table->text('notice_text')->nullable();
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
        Schema::dropIfExists('contact_page_details');
    }
};
