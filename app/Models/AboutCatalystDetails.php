<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutCatalystDetails extends Model
{
    use HasFactory;

    protected $table = 'about_catalyst_details';
    public $timestamps = false;

    protected $fillable = [
        'sub_heading',
        'heading',
        'description',
        'button_text',
        'button_link',
        'features',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    protected $casts = [
        'features' => 'array',
    ];
}
