<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('category')->nullable();        // group heading e.g. "Breach of Minimum Security Cover"
            $table->string('title');                        // notice / company name
            $table->text('description')->nullable();        // optional paragraph
            $table->string('notice_date')->nullable();      // free text e.g. "September 2025"
            $table->string('document_file')->nullable();    // uploaded PDF
            $table->string('document_link')->nullable();    // external URL
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);      // 1 = show, 0 = hide

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
        Schema::dropIfExists('notices');
    }
};
