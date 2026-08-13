<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactOffice extends Model
{
    use HasFactory;

    protected $table = 'contact_offices';
    public $timestamps = false;

    const TYPES = [
        'main'   => 'Main Branch Office',
        'branch' => 'Other Branch Office',
    ];

    protected $fillable = [
        'type',
        'city',
        'role',
        'address',
        'contact',
        'email',
        'map_link',
        'tag',
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
