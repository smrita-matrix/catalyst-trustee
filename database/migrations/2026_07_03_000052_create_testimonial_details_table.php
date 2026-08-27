<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Home page "Testimonials" carousel. One row holds the section heading and
     * the slides, matching how the other repeating home sections are stored.
     */
    public function up(): void
    {
        Schema::create('testimonial_details', function (Blueprint $table) {
            $table->id();
            $table->string('heading')->nullable();
            $table->longText('items')->nullable(); // JSON array of { text, name, designation, company }

            // Custom audit columns (model has $timestamps = false)
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
        Schema::dropIfExists('testimonial_details');
    }
};
