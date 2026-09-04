<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrievancePageDetails extends Model
{
    use HasFactory;

    protected $table = 'grievance_page_details';
    public $timestamps = false;

    protected $fillable = [
        'banner_title',
        'breadcrumb_child',
        'banner_image',
        'intro_text',
        'sebi_heading',
        'sebi_intro',
        'sebi_officer_name',
        'sebi_officer_phone',
        'sebi_officer_email',
        'non_sebi_heading',
        'non_sebi_intro',
        'non_sebi_officer_name',
        'non_sebi_officer_phone',
        'non_sebi_officer_email',
        'non_sebi_note',
        'holder_heading',
        'instrument_heading',
        'complaint_options',
        'notes',
        'support_pdf',
        'notify_email',
        'notify_cc',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    protected $casts = [
        'complaint_options' => 'array',
        'notes'             => 'array',
    ];

    /** URL of the PDF the "Contact for Support" menu item opens, if one is uploaded. */
    public function getSupportPdfUrlAttribute()
    {
        return $this->support_pdf ? asset('grievance/documents/' . $this->support_pdf) : null;
    }
}
