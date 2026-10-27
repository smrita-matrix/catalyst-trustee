<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** The "Our Global Presence" strip under the home banner. */
class GlobalPresenceDetails extends Model
{
    protected $table = 'global_presence_details';
    public $timestamps = false;

    protected $fillable = [
        'eyebrow',
        'heading',
        'description',
        'button_text',
        'button_link',
        'background_image',
        'items',
        'status',
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
