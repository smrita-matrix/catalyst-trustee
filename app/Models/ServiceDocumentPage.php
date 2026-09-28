<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceDocumentPage extends Model
{
    use HasFactory;

    protected $table = 'service_document_pages';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'banner_title', 'banner_breadcrumb_parent', 'banner_breadcrumb_child', 'banner_background_image',
        'page_title', 'page_intro',
        'created_at', 'created_by', 'modified_at', 'modified_by', 'deleted_at', 'deleted_by',
    ];
}
