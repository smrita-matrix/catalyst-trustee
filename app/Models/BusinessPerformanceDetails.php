<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessPerformanceDetails extends Model
{
    use HasFactory;

    protected $table = 'business_performance_details';
    public $timestamps = false;

    protected $fillable = [
        'sub_heading',
        'heading',
        'categories',
        'years',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    protected $casts = [
        'categories' => 'array',
        'years'      => 'array',
    ];
}
