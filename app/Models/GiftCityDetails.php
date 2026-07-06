<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCityDetails extends Model
{
    use HasFactory;

    protected $table = 'gift_city_details';
    public $timestamps = false;

    protected $fillable = [
        'heading',
        'footer_text',
        'items',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    protected $casts = [
        'items' => 'array',
    ];
}
