<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('debenture_trustee_listed_details', function (Blueprint $table) {
            $table->text('recognition_heading')->nullable()->after('services_offered_tabs');
            $table->longText('certificates')->nullable()->after('recognition_heading');   // JSON [{ image, alt }]
            $table->longText('recognition_note')->nullable()->after('certificates');       // rich text note strip
        });
    }

    public function down(): void
    {
        Schema::table('debenture_trustee_listed_details', function (Blueprint $table) {
            $table->dropColumn(['recognition_heading', 'certificates', 'recognition_note']);
        });
    }
};
