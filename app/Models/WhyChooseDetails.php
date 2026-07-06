<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhyChooseDetails extends Model
{
    use HasFactory;

    protected $table = 'why_choose_details';
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
