<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Resumes submitted through the Careers form. */
    public function up(): void
    {
        Schema::create('career_applications', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('city');
            $table->string('position');
            $table->text('intro')->nullable();
            $table->string('resume_file');           // stored CV
            $table->string('resume_original_name')->nullable();
            $table->tinyInteger('is_read')->default(0);
            $table->string('ip_address', 45)->nullable();

            $table->timestamp('created_at')->nullable();
            $table->timestamp('modified_at')->nullable();
            $table->unsignedBigInteger('modified_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->index('is_read');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_applications');
    }
};
