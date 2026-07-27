<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_category_id')->nullable();  // parent Service Category
            $table->string('name');                                         // product name
            $table->string('slug')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(1);                          // 1 = show, 0 = hide

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
        Schema::dropIfExists('product_categories');
    }
};
