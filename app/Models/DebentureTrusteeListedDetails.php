<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebentureTrusteeListedDetails extends Model
{
    use HasFactory;

    protected $table = 'debenture_trustee_listed_details';
    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'product_id',
        // Banner
        'banner_title',
        'banner_breadcrumb_parent',
        'banner_breadcrumb_child',
        'banner_background_image',
        // Intro
        'intro_image',
        'intro_heading',
        'intro_description',
        'intro_expertise_heading',
        'intro_expertise_points',
        // Our Services Include
        'services_include_image',
        'services_include_heading',
        'services_include_points',
        // Why Catalyst
        'why_heading',
        'why_cards',
        // Services Offered
        'services_offered_heading',
        'services_offered_tabs',
        // Recognition & Registration
        'recognition_heading',
        'certificates',
        'recognition_note',
        // Audit
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    protected $casts = [
        'why_cards'             => 'array',
        'services_offered_tabs' => 'array',
        'certificates'          => 'array',
    ];
}
