<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * "Our Global Presence" - the strip under the home banner that names the
 * financial centres Catalyst works from, each with its landmark drawing.
 *
 * One row holds the section; its locations are a list, so a centre can be
 * added or dropped from the dashboard without touching the page.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('global_presence_details', function (Blueprint $table) {
            $table->id();

            $table->string('eyebrow')->nullable();
            $table->string('heading')->nullable();
            $table->text('description')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_link', 500)->nullable();
            // Faint picture behind the strip; empty uses the dotted world map.
            $table->string('background_image')->nullable();
            // [{icon, name, link}, ...]
            $table->json('items')->nullable();
            $table->tinyInteger('status')->default(1);

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('modified_at')->nullable();
            $table->unsignedBigInteger('modified_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        // Start from the approved design, so the section shows straight away.
        DB::table('global_presence_details')->insert([
            'eyebrow'     => 'Our Global Presence',
            'heading'     => 'Extending Our Expertise Across Key Financial Centres',
            'description' => 'Delivering trusted fiduciary and financial services with an international outlook.',
            'button_text' => 'Explore Our Global Presence',
            'button_link' => 'group-companies',
            'items'       => json_encode([
                ['icon' => 'gp_mauritius.svg', 'name' => 'Mauritius',   'link' => null],
                ['icon' => 'gp_dubai.svg',     'name' => 'Dubai, UAE',  'link' => null],
                ['icon' => 'gp_singapore.svg', 'name' => 'Singapore',   'link' => null],
            ]),
            'status'      => 1,
            'created_at'  => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('global_presence_details');
    }
};
