<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A legal / informational page — Privacy Policy, Terms of Use, Disclaimer.
 *
 * The body is a list of {heading, body} blocks rather than one big HTML field,
 * so the admin screen stays a simple repeater and the frontend can style every
 * section consistently.
 */
class PolicyPage extends Model
{
    use HasFactory;

    protected $table = 'policy_pages';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'slug',
        'breadcrumb_child',
        'banner_image',
        'intro_text',
        'sections',
        'effective_on',
        'show_in_footer',
        'status',
        'sort_order',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    protected $casts = [
        'sections'       => 'array',
        'effective_on'   => 'date',
        'show_in_footer' => 'boolean',
        'status'         => 'boolean',
    ];

    /** Published pages only. */
    public function scopeLive($query)
    {
        return $query->whereNull('deleted_at')->where('status', 1);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function getUrlAttribute(): string
    {
        return route('frontend.policy', $this->slug);
    }

    public function getBannerUrlAttribute(): ?string
    {
        return $this->banner_image ? asset('policy/banner/' . $this->banner_image) : null;
    }
}
