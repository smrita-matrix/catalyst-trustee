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
        Schema::create('leadership_content_details', function (Blueprint $table) {
            $table->id();

            // Intro block
            $table->string('intro_sub_heading')->nullable();  // e.g. ABOUT
            $table->text('intro_heading')->nullable();         // e.g. Leadership
            $table->longText('intro_description')->nullable();

            // Board of Directors
            $table->string('board_heading')->nullable();       // e.g. Board of Directors
            $table->longText('board_members')->nullable();     // JSON [{ image, name, designation, description }]

            // Leadership Team
            $table->string('team_heading')->nullable();        // e.g. Leadership Team
            $table->longText('team_members')->nullable();      // JSON [{ image, name, designation, description }]

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
        Schema::dropIfExists('leadership_content_details');
    }
};
