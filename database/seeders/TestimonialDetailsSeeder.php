<?php

namespace Database\Seeders;

use App\Models\TestimonialDetails;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * Loads the four testimonials that were previously hard-coded on the home page,
 * so the section keeps showing the same content once it is driven by the admin.
 * Safe to re-run: it only creates the row when none exists.
 */
class TestimonialDetailsSeeder extends Seeder
{
    public function run(): void
    {
        if (TestimonialDetails::whereNull('deleted_at')->exists()) {
            return;
        }

        TestimonialDetails::create([
            'heading' => 'Testimonials',
            'items'   => [
                [
                    'text'        => 'Catalyst Trusteeship Limited has been a dependable partner across our structured finance engagements. Their ability to manage complex transactions with precision, coupled with strong regulatory oversight, ensures seamless execution and instills confidence across all stakeholders.',
                    'name'        => 'Rakesh Punamiya',
                    'designation' => 'VP - Finance',
                    'company'     => 'JSW Energy Limited',
                ],
                [
                    'text'        => "I would like to express my sincere appreciation for the professionalism and efficiency demonstrated by the Catalyst team. Throughout the tenure of our financing arrangements, Catalyst always maintains a high standard of diligence, transparency, and responsiveness.\nTheir ability to coordinate seamlessly between lenders and the borrower has always ensured smooth execution of documentation, timely compliance monitoring, and effective resolution of any operational matters. The structured approach and clear communication provided confidence and stability at every stage of the transaction.\nThe team consistently has upheld its fiduciary responsibilities with integrity, safeguarding the interests of all stakeholders while also facilitating practical and timely solutions.",
                    'name'        => 'Tarun Bajaj',
                    'designation' => 'GM - Finance',
                    'company'     => 'ReNew',
                ],
                [
                    'text'        => 'Our experience with Catalyst has been seamless across multiple transactions. Their team ensures efficient execution, clear communication, and timely closure, which is critical in today’s fast-paced market environment.',
                    'name'        => 'Devesh Khatore',
                    'designation' => 'AGM - Project Finance',
                    'company'     => 'ACEN India',
                ],
                [
                    'text'        => "We have had a strong and enduring association with Catalyst Trusteeship, engaging them as both Debenture Trustee for our portfolio entities and as AIF Trustees. Their disciplined approach to investment and compliance has consistently inspired confidence across our engagements.\nTheir professionalism and pragmatic approach make them a dependable partner. We deeply value the strength and continuity of this relationship.",
                    'name'        => 'Vimal Sota',
                    'designation' => 'Executive Director Legal & Compliance',
                    'company'     => 'Everstone',
                ],
            ],
            'created_at' => Carbon::now(),
        ]);
    }
}
