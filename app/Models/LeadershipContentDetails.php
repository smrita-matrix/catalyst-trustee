<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadershipContentDetails extends Model
{
    use HasFactory;

    protected $table = 'leadership_content_details';
    public $timestamps = false;

    protected $fillable = [
        'intro_sub_heading',
        'intro_heading',
        'intro_description',
        'board_heading',
        'board_members',
        'team_heading',
        'team_members',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    protected $casts = [
        'board_members' => 'array',
        'team_members'  => 'array',
    ];
}
