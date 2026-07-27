<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupCompaniesDifcDetails extends Model
{
    use HasFactory;

    protected $table = 'group_companies_difc_details';
    public $timestamps = false;

    protected $fillable = [
        'logo_image',
        'heading',
        'top_description',
        'services',
        'bottom_description',
        'button_text',
        'button_link',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    protected $casts = [
        'services' => 'array',
    ];
}
