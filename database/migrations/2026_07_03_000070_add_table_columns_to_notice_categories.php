<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Column headings for the table layout.
     *
     * The table shows three columns, and what they are called depends on the
     * page - "Issuer Name / Issued By / Date" on the credit ratings page, but
     * something else on the next page that uses this layout. Storing the
     * headings keeps that out of the template.
     */
    public function up(): void
    {
        Schema::table('notice_categories', function (Blueprint $table) {
            $table->string('col_one_label')->nullable()->after('layout');
            $table->string('col_two_label')->nullable()->after('col_one_label');
            $table->string('col_three_label')->nullable()->after('col_two_label');
        });
    }

    public function down(): void
    {
        Schema::table('notice_categories', function (Blueprint $table) {
            $table->dropColumn(['col_one_label', 'col_two_label', 'col_three_label']);
        });
    }
};
