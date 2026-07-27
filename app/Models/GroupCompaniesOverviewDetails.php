<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupCompaniesOverviewDetails extends Model
{
    use HasFactory;

    protected $table = 'group_companies_overview_details';
    public $timestamps = false;

    protected $fillable = [
        'main_image',
        'small_image',
        'heading',
        'description',
        'entities',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    protected $casts = [
        'entities' => 'array',
    ];
}
