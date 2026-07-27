<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Consolidate the whole "Debenture Trustee (Listed)" page into ONE table.
     */
    public function up(): void
    {
        Schema::dropIfExists('debenture_trustee_overview_details');
        Schema::dropIfExists('debenture_trustee_listed_details');

        Schema::create('debenture_trustee_listed_details', function (Blueprint $table) {
            $table->id();

            // ---- Banner ----
            $table->string('banner_title')->nullable();
            $table->string('banner_breadcrumb_parent')->nullable();
            $table->string('banner_breadcrumb_child')->nullable();
            $table->string('banner_background_image')->nullable();

            // ---- Intro (image + heading + description + expertise list) ----
            $table->string('intro_image')->nullable();
            $table->text('intro_heading')->nullable();
            $table->longText('intro_description')->nullable();
            $table->string('intro_expertise_heading')->nullable();
            $table->longText('intro_expertise_points')->nullable();   // one point per line

            // ---- Our Services Include ----
            $table->string('services_include_image')->nullable();
            $table->text('services_include_heading')->nullable();
            $table->longText('services_include_points')->nullable();  // one point per line

            // ---- Why Catalyst Trustee Services? ----
            $table->text('why_heading')->nullable();
            $table->longText('why_cards')->nullable();                // JSON [{ icon, title }]

            // ---- Services Offered (tabs) ----
            $table->text('services_offered_heading')->nullable();
            $table->longText('services_offered_tabs')->nullable();    // JSON [{ title, image, points }]

            // ---- Audit columns (model has $timestamps = false) ----
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
        Schema::dropIfExists('debenture_trustee_listed_details');
    }
};
