<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Plain text pages the legal team maintains — Privacy Policy today, and
     * Terms of Use / Disclaimer later. Each row is one page; the body is a
     * JSON list of {heading, body} blocks so the admin can add sections
     * without touching a template.
     */
    public function up(): void
    {
        Schema::create('policy_pages', function (Blueprint $table) {
            $table->id();

            $table->string('title');                        // "Privacy Policy"
            $table->string('slug')->unique();               // "privacy-policy" -> /privacy-policy
            $table->string('breadcrumb_child')->nullable();
            $table->string('banner_image')->nullable();

            $table->text('intro_text')->nullable();         // paragraph above the sections
            $table->longText('sections')->nullable();       // JSON: [{heading, body}, ...]
            $table->date('effective_on')->nullable();       // "Last updated" line

            $table->boolean('show_in_footer')->default(1);
            $table->boolean('status')->default(1);
            $table->integer('sort_order')->default(0);

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
        Schema::dropIfExists('policy_pages');
    }
};
