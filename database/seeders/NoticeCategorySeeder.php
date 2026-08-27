<?php

namespace Database\Seeders;

use App\Models\Notice;
use App\Models\NoticeCategory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NoticeCategorySeeder extends Seeder
{
    /**
     * Builds the Public Notice menu exactly as supplied by the client.
     * Safe to re-run: rows are matched on slug (or name for headings), never duplicated.
     * Everything here is editable from the admin afterwards.
     */
    public function run(): void
    {
        $tree = [
            [
                'name' => 'Notices & Announcements',
                'icon' => 'notices-and-announcements-icon.webp',
                'children' => [
                    [
                        'name' => 'Breach of Minimum Security Cover', 'slug' => 'breach-of-minimum-security-cover', 'layout' => 'bomsc',
                        'alert_heading' => 'Attention Investors!',
                        'alert_text'    => 'If the link for the scheduled breach of minimum security cover meeting is not received, please write to us at <a href="mailto:response.dt@ctltrustee.com">response.dt@ctltrustee.com</a>',
                    ],
                    [
                        'name' => 'Breach Of Covenants', 'slug' => 'breach-of-covenants', 'layout' => 'boc',
                        'alert_heading' => 'Attention Investors!',
                        'alert_text'    => 'If the link for the scheduled breach of covenant meeting is not received, please write to us at <a href="mailto:BOC_Team@ctltrustee.com">BOC_Team@ctltrustee.com</a>',
                    ],
                    ['name' => 'Auction Notices', 'slug' => 'auction-notices', 'layout' => 'auc'],
                ],
            ],
            [
                'name' => 'Regulatory Disclosures',
                'icon' => 'regulatory-disclosures-icon.webp',
                'children' => [
                    [
                        // Heading only — opens the flyout, not a page of its own.
                        'name'      => 'SEBI Compliance by Debenture Trustee',
                        'link_type' => 'none',
                        'children'  => [
                            [
                                'name' => 'Revision in Credit Ratings', 'slug' => 'revision-in-credit-ratings', 'layout' => 'list',
                                'alert_heading' => 'Disclosure as per SEBI Circular dated 12-11-2020',
                                'alert_text'    => '(SEBI/ HO/ MIRSD/ CRADT/ CIR/ P/ 2020/230 dated November 12, 2020 as per Annexure B : Table 1 Revision in Credit Ratings)',
                            ],
                            ['name' => 'Status of Payment of Interest & Principal',     'slug' => 'status-of-payment-of-interest-and-principal',    'layout' => 'status'],
                            ['name' => 'Monitoring of Utilization Certificate',         'slug' => 'monitoring-of-utilization-certificate',          'layout' => 'grouped'],
                            ['name' => 'Security Cover Certificate',                    'slug' => 'security-cover-certificate',                     'layout' => 'grouped'],
                            ['name' => 'Periodical Status/ Performance Reports/ Quarterly Compliance Reports/ Financial Statement', 'slug' => 'periodical-status-performance-reports-quarterly-compliance-reports-financial-statement', 'layout' => 'grouped'],
                            ['name' => 'Details of Debenture issues handled by debenture trustee and their status', 'slug' => 'details-of-debenture-issues-handled-by-debenture-trustee-and-their-status', 'layout' => 'grouped'],
                            ['name' => 'Status of information regarding breach of covenants/terms of the issue',    'slug' => 'status-of-information-regarding-breach-of-covenants-terms-of-the-issue',   'layout' => 'grouped'],
                            ['name' => 'Complaints received including default cases',   'slug' => 'complaints-received-including-default-cases',    'layout' => 'grouped'],
                            ['name' => 'Debenture Redemption Reserve',                  'slug' => 'debenture-redemption-reserve',                   'layout' => 'grouped'],
                            ['name' => 'Debenture Redemption Fund',                     'slug' => 'debenture-redemption-fund',                      'layout' => 'grouped'],
                            ['name' => 'Recovery Expenses Fund',                        'slug' => 'recovery-expenses-fund',                         'layout' => 'grouped'],
                            ['name' => 'Monitoring of Accounts / Fund – Municipal Debt Securities', 'slug' => 'monitoring-of-accounts-fund-municipal-debt-securities', 'layout' => 'grouped'],
                            ['name' => 'Information Regarding Defaulted Cases',         'slug' => 'information-on-defaulted-cases',                 'layout' => 'grouped'],
                        ],
                    ],
                    ['name' => 'Investor Charter - Debenture Trustee',            'slug' => 'investor-charter-debenture-trustee',   'layout' => 'list'],
                    ['name' => 'Half Yearly Compliance Reports submitted to SEBI','slug' => 'periodical-statements-submitted-to-sebi', 'layout' => 'grouped'],
                    // Client is supplying a PDF for this one — upload it from the admin.
                    ['name' => 'Fees Structure',                                  'link_type' => 'pdf'],
                    ['name' => 'Policies',                                        'slug' => 'policies',                            'layout' => 'list'],
                    ['name' => 'DSDKL Updates',                                   'slug' => 'dskdl-developers-ltd',                'layout' => 'grouped'],
                    ['name' => 'SREI Updates',                                    'slug' => 'sefl-sifl-ibc',                       'layout' => 'list'],
                ],
            ],
        ];

        foreach ($tree as $i => $column) {
            $this->saveNode($column, null, $i + 1);
        }

        $this->linkExistingNotices();
    }

    /** Create/update one node and recurse into its children. */
    private function saveNode(array $node, ?int $parentId, int $sortOrder): void
    {
        $match = isset($node['slug'])
            ? ['slug' => $node['slug']]
            : ['name' => $node['name'], 'parent_id' => $parentId];

        $category = NoticeCategory::where($match)->first();

        $values = [
            'parent_id'  => $parentId,
            'name'       => $node['name'],
            'slug'       => $node['slug']      ?? null,
            'icon'       => $node['icon']      ?? null,
            'link_type'  => $node['link_type'] ?? (isset($node['children']) ? 'none' : 'page'),
            'layout'     => $node['layout']    ?? null,
            'page_title'    => $node['name'],
            'alert_heading' => $node['alert_heading'] ?? null,
            'alert_text'    => $node['alert_text']    ?? null,
            'sort_order'    => $sortOrder,
            'status'     => 1,
        ];

        if ($category) {
            // Keep any content the admin has already edited — only refresh the wiring.
            $category->update(array_merge($values, [
                'page_title'    => $category->page_title    ?: $node['name'],
                'alert_heading' => $category->alert_heading ?: ($node['alert_heading'] ?? null),
                'alert_text'    => $category->alert_text    ?: ($node['alert_text']    ?? null),
                'modified_at'   => Carbon::now(),
            ]));
        } else {
            $category = NoticeCategory::create($values + ['created_at' => Carbon::now()]);
        }

        foreach (($node['children'] ?? []) as $j => $child) {
            $this->saveNode($child, $category->id, $j + 1);
        }
    }

    /** Move notices created under the old hard-coded sections onto their new category. */
    private function linkExistingNotices(): void
    {
        $map = [
            'bomsc' => 'breach-of-minimum-security-cover',
            'boc'   => 'breach-of-covenants',
            'auc'   => 'auction-notices',
        ];

        foreach ($map as $section => $slug) {
            $categoryId = NoticeCategory::where('slug', $slug)->value('id');
            if ($categoryId) {
                Notice::where('section', $section)
                    ->whereNull('notice_category_id')
                    ->update(['notice_category_id' => $categoryId]);
            }
        }
    }
}
