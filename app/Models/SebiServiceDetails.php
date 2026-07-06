<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SebiServiceDetails extends Model
{
    use HasFactory;

    protected $table = 'sebi_service_details';
    public $timestamps = false;

    protected $fillable = [
        'heading',
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
