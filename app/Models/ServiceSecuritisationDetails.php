<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceSecuritisationDetails extends Model
{
    use HasFactory;

    protected $table = 'service_securitisation_details';
    public $timestamps = false;

    /** The repeating parts of the page are kept as lists. */
    protected $casts = [
        'panel_blocks'    => 'array',
        'glance_cards'    => 'array',
        'capability_tabs' => 'array',
        'lifecycle_steps' => 'array',
    ];

    protected $fillable = [
        'product_id',
        'banner_title',
        'banner_breadcrumb_parent',
        'banner_breadcrumb_child',
        'banner_background_image',

        'intro_image',
        'intro_heading',
        'intro_subheading',
        'intro_description',
        'intro_image_side',
        'intro_background',

        'business_image',
        'business_heading',
        'business_subheading',
        'business_description',
        'business_image_side',
        'business_background',

        'closing_image',
        'closing_heading',
        'closing_subheading',
        'closing_description',
        'closing_image_side',
        'closing_background',

        'panel_image',
        'panel_image_side',
        'panel_blocks',
        'panel_points',

        'glance_heading',
        'glance_cards',

        'capabilities_heading',
        'capability_tabs',

        'lifecycle_heading',
        'lifecycle_steps',

        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    /** The three bands that are a picture beside some words, in page order. */
    public const BANDS = ['intro', 'business', 'closing'];
}
