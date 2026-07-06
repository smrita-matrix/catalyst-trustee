<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtaDetails extends Model
{
    use HasFactory;

    protected $table = 'cta_details';
    public $timestamps = false;

    protected $fillable = [
        'heading',
        'description',
        'button_text',
        'button_link',
        'background_image',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];
}
