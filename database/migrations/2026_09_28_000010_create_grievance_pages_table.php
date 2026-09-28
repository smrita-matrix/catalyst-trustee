<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The pages under Grievance.
 *
 * There were two of these, each with its own address, its own template and
 * its own pair of columns on the settings row. Adding a third meant touching
 * all of that, so the pages become rows instead: the menu, the addresses and
 * the pages themselves all follow this table, and another one can be added
 * from the dashboard alone.
 *
 * A page shows whichever of the two grievance forms it is set to, or none at
 * all where it is simply something to read.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grievance_pages', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);

            $table->string('banner_title')->nullable();
            $table->string('breadcrumb_child')->nullable();
            $table->string('banner_image')->nullable();

            $table->string('heading')->nullable();
            $table->text('intro')->nullable();

            /* For a page that is simply something to read. */
            $table->longText('body')->nullable();

            /* none | sebi | non_sebi */
            $table->string('form_type', 20)->default('none');

            /* The people to write to: [{ label, value, kind }]. */
            $table->longText('contacts')->nullable();
            $table->text('note')->nullable();

            /* Something to open or download alongside the page. */
            $table->string('document_file')->nullable();
            $table->string('document_label')->nullable();
            $table->string('external_link')->nullable();

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
        Schema::dropIfExists('grievance_pages');
    }
};
