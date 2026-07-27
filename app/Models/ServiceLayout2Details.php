<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceLayout2Details extends Model
{
    use HasFactory;

    protected $table = 'service_layout2_details';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'banner_breadcrumb_parent',
        'banner_breadcrumb_child',
        'banner_background_image',
        'nature_image',
        'nature_heading',
        'nature_description',
        'process_image',
        'process_heading',
        'process_points',
        'keyfacts_image',
        'keyfacts_heading',
        'keyfacts_points',
        'keyfacts_note',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];
}
