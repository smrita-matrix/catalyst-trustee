<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The page behind the two Securitisation services.
 *
 * The design gives Listed PTC seven bands and Unlisted four, but they are
 * drawn from the same handful of shapes, so one layout covers both: a page
 * fills in the bands it needs and leaves the rest empty, and an empty band
 * does not appear. Where the design flips a picture from one side to the
 * other, or drops the tint behind a band, that is a setting rather than a
 * second template.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_securitisation_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable()->index();

            $table->string('banner_title')->nullable();
            $table->string('banner_breadcrumb_parent')->nullable();
            $table->string('banner_breadcrumb_child')->nullable();
            $table->string('banner_background_image')->nullable();

            /* Three bands of the same shape: a picture beside some words. */
            foreach (['intro', 'business', 'closing'] as $band) {
                $table->string($band . '_image')->nullable();
                $table->text($band . '_heading')->nullable();
                $table->string($band . '_subheading')->nullable();
                $table->longText($band . '_description')->nullable();
                $table->string($band . '_image_side', 10)->nullable();   // left | right
                $table->string($band . '_background', 10)->nullable();   // tint | white
            }

            /* The full-width panel: a picture, and headed paragraphs beside it. */
            $table->string('panel_image')->nullable();
            $table->string('panel_image_side', 10)->nullable();
            $table->longText('panel_blocks')->nullable();                // [{ heading, description }]
            $table->longText('panel_points')->nullable();

            /* "Our Experience at a Glance". */
            $table->text('glance_heading')->nullable();
            $table->longText('glance_cards')->nullable();                // [{ icon, value, label }]

            /* The tabbed capabilities panel. */
            $table->text('capabilities_heading')->nullable();
            $table->longText('capability_tabs')->nullable();             // [{ icon, title, description, points, flow }]

            /* The lifecycle strip. */
            $table->text('lifecycle_heading')->nullable();
            $table->longText('lifecycle_steps')->nullable();             // [{ icon, title }]

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
        Schema::dropIfExists('service_securitisation_details');
    }
};
