<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceLayout3Details extends Model
{
    use HasFactory;

    protected $table = 'service_layout3_details';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'banner_breadcrumb_parent',
        'banner_breadcrumb_child',
        'banner_background_image',
        'intro_image',
        'intro_heading',
        'intro_description',
        'services_divider_label',
        'services_tabs',
        'benefits_image',
        'benefits_heading',
        'benefits_points',
        'benefits_note',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    protected $casts = [
        'services_tabs' => 'array',
    ];
}
