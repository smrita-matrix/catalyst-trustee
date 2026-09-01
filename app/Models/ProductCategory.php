<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $table = 'product_categories';
    public $timestamps = false;

    /** Available page layouts (template used to render/edit the product's page). */
    public const LAYOUTS = [
        'debenture'  => 'Layout 1',
        'services2'  => 'Layout 2',
        'services3'  => 'Layout 3',
        'fif'        => 'Layout 4',
    ];

    protected $fillable = [
        'service_category_id',
        'name',
        'slug',
        'layout',
        'sort_order',
        'status',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    /**
     * The Service Category this product belongs to.
     */
    public function serviceCategory()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    /**
     * Public address of this service page.
     *
     * The group is part of the address — /services/gift-city-services/facility-agent —
     * because the same service can sit under more than one group. "Facility Agent"
     * appears under both Non SEBI and GIFT City, and without the group in the
     * address only one of them could ever be opened. It also matches the
     * breadcrumb shown on the page.
     *
     * Returns null when either slug is missing, so callers can fall back.
     */
    public function getUrlAttribute(): ?string
    {
        $categorySlug = optional($this->serviceCategory)->slug;

        if (!$categorySlug || !$this->slug) {
            return null;
        }

        return route('frontend.product_page', [$categorySlug, $this->slug]);
    }
}
