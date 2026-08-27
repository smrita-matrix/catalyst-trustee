<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerOpening extends Model
{
    use HasFactory;

    protected $table = 'career_openings';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'experience',
        'vacancies',
        'qualification',
        'location',
        'description',
        'sort_order',
        'status',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];
}
