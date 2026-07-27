<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OurJourneyMilestoneDetails extends Model
{
    use HasFactory;

    protected $table = 'our_journey_milestone_details';
    public $timestamps = false;

    protected $fillable = [
        'year',
        'description',
        'icon_image',
        'sort_order',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];
}
