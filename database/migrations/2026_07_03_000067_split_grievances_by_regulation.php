<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The grievance page now has two forms, one for services SEBI regulates and
     * one for those it does not. They ask for different things, so a submission
     * records which form it came from and carries the two fields only the SEBI
     * form asks for.
     *
     * Everything already submitted came from the non-SEBI form, so existing
     * rows are marked as such rather than left blank.
     */
    public function up(): void
    {
        Schema::table('grievances', function (Blueprint $table) {
            $table->string('type', 20)->default('non_sebi')->after('id');
            $table->text('investment_details')->nullable()->after('bonds_held');
            $table->text('nature_of_complaint')->nullable()->after('investment_details');
        });

        Schema::table('grievance_page_details', function (Blueprint $table) {
            // Wording and the officer named at the foot of each form.
            $table->string('sebi_heading')->nullable()->after('intro_text');
            $table->text('sebi_intro')->nullable()->after('sebi_heading');
            $table->string('sebi_officer_name')->nullable()->after('sebi_intro');
            $table->string('sebi_officer_phone')->nullable()->after('sebi_officer_name');
            $table->string('sebi_officer_email')->nullable()->after('sebi_officer_phone');

            $table->string('non_sebi_heading')->nullable()->after('sebi_officer_email');
            $table->text('non_sebi_intro')->nullable()->after('non_sebi_heading');
            $table->string('non_sebi_officer_name')->nullable()->after('non_sebi_intro');
            $table->string('non_sebi_officer_phone')->nullable()->after('non_sebi_officer_name');
            $table->string('non_sebi_officer_email')->nullable()->after('non_sebi_officer_phone');
            $table->text('non_sebi_note')->nullable()->after('non_sebi_officer_email');
        });
    }

    public function down(): void
    {
        Schema::table('grievances', function (Blueprint $table) {
            $table->dropColumn(['type', 'investment_details', 'nature_of_complaint']);
        });

        Schema::table('grievance_page_details', function (Blueprint $table) {
            $table->dropColumn([
                'sebi_heading', 'sebi_intro', 'sebi_officer_name', 'sebi_officer_phone', 'sebi_officer_email',
                'non_sebi_heading', 'non_sebi_intro', 'non_sebi_officer_name', 'non_sebi_officer_phone',
                'non_sebi_officer_email', 'non_sebi_note',
            ]);
        });
    }
};
