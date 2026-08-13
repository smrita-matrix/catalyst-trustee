<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_offices', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('branch'); // main | branch
            $table->string('city')->nullable();
            $table->string('role')->nullable();        // e.g. "Corporate Office" (main only)
            $table->text('address')->nullable();
            $table->string('contact')->nullable();     // phone (main)
            $table->string('email')->nullable();
            $table->string('map_link')->nullable();
            $table->string('tag')->nullable();         // e.g. "PAN India"
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
        Schema::dropIfExists('contact_offices');
    }
};
