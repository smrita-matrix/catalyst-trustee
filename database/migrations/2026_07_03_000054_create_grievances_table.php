<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Investor grievance submissions from the public form. */
    public function up(): void
    {
        Schema::create('grievances', function (Blueprint $table) {
            $table->id();

            // Investor / debenture holder
            $table->string('full_name');
            $table->string('pan');
            $table->string('email');
            $table->string('mobile')->nullable();
            $table->text('address');

            // Instrument details
            $table->string('issuer_name');
            $table->string('series_name')->nullable();
            $table->string('isin');
            $table->string('bonds_held');

            // The grievance itself
            $table->longText('complaint_types')->nullable(); // JSON list of the ticked options
            $table->text('complaint_details');

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
        Schema::dropIfExists('grievances');
    }
};
