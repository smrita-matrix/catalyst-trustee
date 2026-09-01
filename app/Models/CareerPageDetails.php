<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerPageDetails extends Model
{
    use HasFactory;

    protected $table = 'career_page_details';
    public $timestamps = false;

    protected $fillable = [
        'banner_title',
        'breadcrumb_child',
        'banner_image',
        'intro_heading',
        'intro_text',
        'life_stories',
        'form_sub_heading',
        'form_heading',
        'notify_email',
        'notify_cc',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    /** "Life at Catalyst" is a list of {title, text, link} entries. */
    protected $casts = [
        'life_stories' => 'array',
    ];
}
