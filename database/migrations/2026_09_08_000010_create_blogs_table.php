<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Blog posts, listed under Articles on the website.
 *
 * Follows the pattern of the other content tables: no Eloquent timestamps,
 * and its own created/modified/deleted columns with the person who did it.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();      // the part of the address that names the post
            $table->text('excerpt')->nullable();   // the few lines shown on the listing
            $table->longText('body')->nullable();  // the post itself
            $table->string('image')->nullable();   // the picture at the top and on the card
            $table->string('author')->nullable();
            $table->date('published_on')->nullable();

            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);

            $table->dateTime('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->dateTime('modified_at')->nullable();
            $table->unsignedBigInteger('modified_by')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
