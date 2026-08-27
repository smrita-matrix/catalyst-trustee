<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grievance extends Model
{
    use HasFactory;

    protected $table = 'grievances';
    public $timestamps = false;

    protected $fillable = [
        'full_name',
        'pan',
        'email',
        'mobile',
        'address',
        'issuer_name',
        'series_name',
        'isin',
        'bonds_held',
        'complaint_types',
        'complaint_details',
        'is_read',
        'ip_address',
        'created_at',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    protected $casts = [
        'complaint_types' => 'array',
    ];
}
