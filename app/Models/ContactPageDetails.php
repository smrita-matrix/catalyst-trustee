<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactPageDetails extends Model
{
    use HasFactory;

    protected $table = 'contact_page_details';
    public $timestamps = false;

    protected $fillable = [
        'banner_title',
        'banner_breadcrumb_parent',
        'banner_background_image',
        'info_heading',
        'phone',
        'phone_link',
        'email',
        'email_link',
        'address',
        'address_link',
        'enquiry_heading',
        'form_heading',
        'form_image',
        'services_options',
        'location_options',
        'office_heading',
        'main_office_subtitle',
        'other_office_subtitle',
        'notice_text',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    /** services_options / location_options stored one-per-line -> array */
    public function optionList($field)
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $this->{$field}))
            ->map(fn ($l) => trim($l))
            ->filter()
            ->values()
            ->all();
    }
}
