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
}
