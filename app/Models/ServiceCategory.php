<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use HasFactory;

    protected $table = 'service_categories';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'menu_items',
        'sort_order',
        'status',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    protected $casts = [
        'menu_items' => 'array',
    ];

    /**
     * Debenture Trustee (Listed) pages that belong to this category.
     */
    public function debenturePages()
    {
        return $this->hasMany(DebentureTrusteeListedDetails::class, 'category_id')
            ->whereNull('deleted_at');
    }
}
