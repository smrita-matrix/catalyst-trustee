<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsMediaBannerDetails extends Model
{
    use HasFactory;

    protected $table = 'news_media_banner_details';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'breadcrumb_parent',
        'breadcrumb_child',
        'background_image',
        'section_heading',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];
}
