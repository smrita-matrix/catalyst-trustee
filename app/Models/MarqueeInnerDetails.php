<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarqueeInnerDetails extends Model
{
    use HasFactory;

    protected $table = 'marquee_inner_details';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];
}
