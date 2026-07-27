<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OurJourneyBannerDetails extends Model
{
    use HasFactory;

    protected $table = 'our_journey_banner_details';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'breadcrumb_parent',
        'background_image',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];
}
