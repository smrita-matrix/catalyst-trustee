<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('year')->nullable();        // group heading e.g. "2025", "Archive"
            $table->string('title');                    // month / label shown on the date-tag
            $table->string('image')->nullable();        // cover thumbnail
            $table->string('pdf_file')->nullable();     // uploaded PDF
            $table->string('pdf_link')->nullable();     // external URL
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);

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
        Schema::dropIfExists('articles');
    }
};
