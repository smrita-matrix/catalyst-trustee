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
        Schema::create('leadership_details', function (Blueprint $table) {
            $table->id();
            $table->string('leadership_heading')->nullable();
            $table->string('numbers_heading')->nullable();
            $table->longText('leaders')->nullable(); // JSON array of { image, name, designation }
            $table->longText('numbers')->nullable(); // JSON array of { icon, count_text, number, suffix }

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
        Schema::dropIfExists('leadership_details');
    }
};
