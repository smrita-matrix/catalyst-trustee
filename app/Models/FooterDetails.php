<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterDetails extends Model
{
    use HasFactory;

    protected $table = 'footer_details';
    public $timestamps = false;

    protected $fillable = [
        'logo',
        'description',
        'phone',
        'email',
        'address',
        'social_links',
        'quick_links',
        'get_started_text',
        'get_started_link',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    protected $casts = [
        'social_links' => 'array',
        'quick_links'  => 'array',
    ];
}
