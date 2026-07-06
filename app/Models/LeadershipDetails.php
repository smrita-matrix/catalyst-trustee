<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadershipDetails extends Model
{
    use HasFactory;

    protected $table = 'leadership_details';
    public $timestamps = false;

    protected $fillable = [
        'leadership_heading',
        'numbers_heading',
        'leaders',
        'numbers',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    protected $casts = [
        'leaders' => 'array',
        'numbers' => 'array',
    ];
}
