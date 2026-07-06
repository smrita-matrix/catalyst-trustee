<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * banner_heading now stores CKEditor (rich text) HTML, so widen it to TEXT.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE banner_details MODIFY banner_heading TEXT NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE banner_details MODIFY banner_heading VARCHAR(255) NOT NULL');
    }
};
