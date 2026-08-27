<?php

namespace Database\Seeders;

use App\Models\PolicyPage;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * Loads the Privacy Policy page.
 *
 * The text describes what this website actually collects — the contact
 * enquiry form, career applications with a CV attached, and the investor
 * grievance form — rather than the WordPress default template that the old
 * site still carries.
 *
 * Re-running is safe. If someone has edited the page in the dashboard
 * (modified_at is set) the seeder leaves it completely alone, so nobody's
 * wording is ever overwritten.
 */
class PolicyPageSeeder extends Seeder
{
    public function run(): void
    {
        $existing = PolicyPage::where('slug', 'privacy-policy')->first();

        if ($existing && $existing->modified_at) {
            $this->command?->info('Privacy Policy has been edited in the dashboard — left untouched.');

            return;
        }

        $payload = [
            'title'            => 'Privacy Policy',
            'breadcrumb_child' => 'Privacy Policy',
            'intro_text'       => $this->intro(),
            'sections'         => $this->sections(),
            'effective_on'     => '2026-08-27',
            'show_in_footer'   => 1,
            'status'           => 1,
            'sort_order'       => 1,
        ];

        if ($existing) {
            $existing->update($payload);

            return;
        }

        PolicyPage::create($payload + [
            'slug'       => 'privacy-policy',
            'created_at' => Carbon::now(),
        ]);
    }

    private function intro(): string
    {
        return "Catalyst Trusteeship Limited (\"Catalyst\", \"we\", \"us\" or \"our\") respects your privacy. "
             . "This policy explains what information we collect through this website, why we collect it, "
             . "how we use and protect it, and the choices available to you.\n\n"
             . "By using this website or submitting any form on it, you agree to the practices described below.";
    }

    /** @return array<int, array{heading: string, body: string}> */
    private function sections(): array
    {
        return [
            [
                'heading' => 'Who we are',
                'body' =>
                    "Catalyst Trusteeship Limited is a SEBI-registered debenture trustee providing trusteeship "
                    . "and fiduciary services in India.\n\n"
                    . "Registered communication address: 901, 9th Floor, Tower-B, Peninsula Business Park, "
                    . "Senapati Bapat Marg, Lower Parel (W), Mumbai 400013.\n\n"
                    . "This policy applies to <a href=\"https://catalysttrustee.com\" target=\"_blank\" rel=\"noopener\">catalysttrustee.com</a> "
                    . "and the forms published on it.",
            ],
            [
                'heading' => 'Information you give us',
                'body' =>
                    "We only collect information that you choose to enter into a form on this website. "
                    . "We do not ask visitors to create an account, and this website does not accept public comments.\n\n"
                    . "<strong>Contact / enquiry form</strong> — when you send us an enquiry we collect:\n\n"
                    . "- Your first and last name\n"
                    . "- Your email address and mobile number\n"
                    . "- The service and location you are enquiring about\n"
                    . "- Any message or comments you write\n\n"
                    . "<strong>Career applications</strong> — when you apply for a role we collect:\n\n"
                    . "- Your first and last name\n"
                    . "- Your email address and phone number\n"
                    . "- Your city and the position you are applying for\n"
                    . "- Your brief introduction and the CV / resume file you upload\n\n"
                    . "<strong>Investor grievance form</strong> — to register and investigate a grievance we collect:\n\n"
                    . "- Your full name, PAN, email address, mobile number and postal address\n"
                    . "- The issuer name, series name, ISIN and number of bonds or debentures held\n"
                    . "- The nature of your complaint and the details you describe\n\n"
                    . "Please share only the information asked for on the form. Do not send passwords, "
                    . "bank account credentials, one-time passwords or other sensitive financial credentials "
                    . "through this website — we will never ask for them.",
            ],
            [
                'heading' => 'Information collected automatically',
                'body' =>
                    "When a form is submitted we record the IP address it was sent from, together with the date "
                    . "and time. This is used to help identify misuse of the form and to maintain a record of "
                    . "when a submission was received.\n\n"
                    . "Like most websites, our web server also logs standard technical information such as browser "
                    . "type and the pages requested.",
            ],
            [
                'heading' => 'How we use your information',
                'body' =>
                    "We use the information you provide only for the purpose you provided it, namely to:\n\n"
                    . "- Respond to your enquiry and contact you about the services you asked about\n"
                    . "- Assess your application for a role and contact you about it\n"
                    . "- Register, investigate, act on and respond to an investor grievance\n"
                    . "- Meet our record-keeping, regulatory and legal obligations\n"
                    . "- Protect the website and our systems against misuse\n\n"
                    . "We do not sell your personal information, and we do not use the details submitted through "
                    . "these forms to send unrelated marketing.",
            ],
            [
                'heading' => 'Cookies',
                'body' =>
                    "This website uses a small number of cookies that are necessary for it to work — for example, "
                    . "to keep your session secure while a form is being submitted.\n\n"
                    . "You can block or delete cookies through your browser settings. If you block essential "
                    . "cookies, some parts of the website — including form submission — may not work correctly.",
            ],
            [
                'heading' => 'Who we share your information with',
                'body' =>
                    "Information submitted through this website is seen by the Catalyst team responsible for "
                    . "handling it — for example the enquiry desk, the human resources team, or the grievance "
                    . "redressal team.\n\n"
                    . "We may share information beyond that only where:\n\n"
                    . "- It is necessary to act on your enquiry or grievance, including with the relevant issuer or intermediary\n"
                    . "- We are required to do so by SEBI, a stock exchange, a court, or any other regulatory or statutory authority\n"
                    . "- It is required by applicable law\n\n"
                    . "We use service providers to host this website and deliver email notifications. They may "
                    . "process the information on our behalf and are required to keep it confidential.",
            ],
            [
                'heading' => 'How long we keep your information',
                'body' =>
                    "We keep your information only for as long as it is needed for the purpose it was collected, "
                    . "and for as long as we are required to retain records under applicable law and the "
                    . "regulations that apply to us as a debenture trustee.\n\n"
                    . "Grievance records in particular are retained for the period prescribed by the applicable "
                    . "regulatory requirements. CVs received through the careers page are retained for our "
                    . "recruitment records and may be considered for future openings.",
            ],
            [
                'heading' => 'How we protect your information',
                'body' =>
                    "We apply reasonable technical and organisational measures to protect the information "
                    . "submitted through this website against unauthorised access, alteration or disclosure, and "
                    . "we limit access to the team members who need it to do their work.\n\n"
                    . "No method of transmission over the internet is completely secure, so while we take "
                    . "protection seriously we cannot guarantee absolute security of information sent to us "
                    . "over the internet.",
            ],
            [
                'heading' => 'Your choices and rights',
                'body' =>
                    "You may write to us to:\n\n"
                    . "- Ask what personal information we hold about you\n"
                    . "- Ask us to correct information that is inaccurate or out of date\n"
                    . "- Ask us to delete information we no longer need to keep\n\n"
                    . "We will act on your request to the extent we are able. Please note that we cannot delete "
                    . "information that we are required to retain for regulatory, legal, audit or security "
                    . "purposes. To make a request, write to us at the address given below.",
            ],
            [
                'heading' => 'Links to other websites',
                'body' =>
                    "This website links to documents and to other websites, including regulator and issuer "
                    . "websites. Those websites are not operated by us, and this policy does not apply to them.\n\n"
                    . "We encourage you to read the privacy policy of any website you visit through a link "
                    . "from ours.",
            ],
            [
                'heading' => 'Investor grievances',
                'body' =>
                    "If you are an investor with a grievance, you can use the "
                    . "<a href=\"/investor-grievance\">Investor Grievance</a> form on this website, or write "
                    . "directly to <a href=\"mailto:grievance@ctltrustee.com\">grievance@ctltrustee.com</a>. "
                    . "If you write to us directly, please include all the details asked for on the grievance form "
                    . "so that we can act on it without delay.\n\n"
                    . "Investors in non-convertible debentures may also register a complaint on the SEBI SCORES "
                    . "portal at <a href=\"https://www.scores.gov.in/\" target=\"_blank\" rel=\"noopener\">https://www.scores.gov.in/</a>.",
            ],
            [
                'heading' => 'Changes to this policy',
                'body' =>
                    "We may update this policy from time to time to reflect changes in our practices or in the "
                    . "law. The date at the top of this page shows when it was last updated. Please review this "
                    . "page periodically.",
            ],
            [
                'heading' => 'Contact us',
                'body' =>
                    "If you have any question about this policy or about how your information is handled, "
                    . "please contact us:\n\n"
                    . "Catalyst Trusteeship Limited\n"
                    . "901, 9th Floor, Tower-B, Peninsula Business Park, Senapati Bapat Marg, "
                    . "Lower Parel (W), Mumbai 400013\n\n"
                    . "Email: <a href=\"mailto:dt.mumbai@ctltrustee.com\">dt.mumbai@ctltrustee.com</a>\n"
                    . "Grievances: <a href=\"mailto:grievance@ctltrustee.com\">grievance@ctltrustee.com</a>\n"
                    . "Phone: <a href=\"tel:+912249220555\">+91 (022) 4922 0555</a>",
            ],
        ];
    }
}
