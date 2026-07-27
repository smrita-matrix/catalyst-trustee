<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyOverviewIntroductionDetails extends Model
{
    use HasFactory;

    protected $table = 'company_overview_introduction_details';
    public $timestamps = false;

    protected $fillable = [
        'image',
        'experience_number',
        'experience_label',
        'established_label',
        'established_year',
        'sub_heading',
        'heading',
        'tagline',
        'description',
        'more_content',
        'button_text',
        'button_link',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];
}
