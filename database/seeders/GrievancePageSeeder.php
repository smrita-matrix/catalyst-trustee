<?php

namespace Database\Seeders;

use App\Models\GrievancePageDetails;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * Loads the Investor Grievance page copy from the approved design so the page
 * is complete from day one. Safe to re-run: it only creates the row when none exists.
 */
class GrievancePageSeeder extends Seeder
{
    public function run(): void
    {
        if (GrievancePageDetails::whereNull('deleted_at')->exists()) {
            return;
        }

        GrievancePageDetails::create([
            'banner_title'       => 'Investor Grievances',
            'breadcrumb_child'   => 'Investor Grievances',
            'intro_text'         => '(This portal is only for investors in Debenture transactions in which Catalyst Trusteeship Limited is Debenture Trustee.)',
            'holder_heading'     => 'Investor/Debenture Holder Details',
            'instrument_heading' => 'Instrument Details & Grievance',
            'complaint_options'  => [
                'Non-Receipt of Interest / Principal',
                'Delay in Receipt of Interest / Principal',
                'Non-Receipt of Debentures',
                'Others',
            ],
            'notes' => [
                'You can directly write to grievance@ctltrustee.com. If communication is sent directly on this mail ID it is necessary to provide all the details as per the Investor Grievance form above.',
                'You may also be aware that NCD investors can also register their complaints on Govt portal at <a href="https://www.scores.gov.in/" target="_blank" rel="noopener">https://www.scores.gov.in/</a>',
            ],
            'created_at' => Carbon::now(),
        ]);
    }
}
