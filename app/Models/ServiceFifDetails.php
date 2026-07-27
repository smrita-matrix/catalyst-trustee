<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceFifDetails extends Model
{
    use HasFactory;

    protected $table = 'service_fif_details';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'banner_breadcrumb_parent',
        'banner_breadcrumb_child',
        'banner_background_image',
        'intro_image',
        'intro_subheading',
        'intro_description',
        'definition_image',
        'definition_description',
        'definition_cards',
        'process_heading',
        'process_tabs',
        'tax_intro',
        'tax_table_html',
        'family_heading',
        'family_description',
        'family_image',
        'capabilities_image',
        'capabilities_heading',
        'capabilities_points',
        'capabilities_disclaimer',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    protected $casts = [
        'definition_cards' => 'array',
        'process_tabs'     => 'array',
    ];
}
