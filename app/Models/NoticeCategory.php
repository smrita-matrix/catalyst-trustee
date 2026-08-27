<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticeCategory extends Model
{
    use HasFactory;

    protected $table = 'notice_categories';
    public $timestamps = false;

    /**
     * Page designs a notice page can be rendered with.
     * The key is stored in `layout` and maps to
     * resources/views/frontend/public-notice/layouts/{key}.blade.php
     */
    public const LAYOUTS = [
        'bomsc'   => 'Layout 1 — Date pills (Breach of Minimum Security Cover)',
        'boc'     => 'Layout 2 — Collapsible months (Breach Of Covenants)',
        'auc'     => 'Layout 3 — Notice cards (Auction Notices)',
        'list'    => 'Layout 4 — Card list (Revision in Credit Ratings, Policies)',
        'grouped' => 'Layout 5 — Collapsible groups (Security Cover Certificate, DSDKL Updates)',
        'status'  => 'Layout 6 — Status of Payment of Interest & Principal',
    ];

    /** What happens when the menu item is clicked. */
    public const LINK_TYPES = [
        'page' => 'Website page (built from the notices below)',
        'pdf'  => 'PDF document',
        'url'  => 'External link',
        'none' => 'Heading only (not clickable)',
    ];

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'icon',
        'link_type',
        'layout',
        'document_file',
        'external_link',
        'page_title',
        'page_intro',
        'banner_image',
        'banner_title',
        'alert_heading',
        'alert_text',
        'sort_order',
        'status',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    /* ----------------------------- Relations ----------------------------- */

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /** Direct children, live rows only, in admin sort order. */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->whereNull('deleted_at')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc');
    }

    /** Notices shown on this category's page. */
    public function notices()
    {
        return $this->hasMany(Notice::class, 'notice_category_id')
            ->whereNull('deleted_at');
    }

    /* ------------------------------ Scopes ------------------------------- */

    public function scopeLive($query)
    {
        return $query->whereNull('deleted_at')->where('status', 1);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }

    /**
     * Every page that can hold notices, as [id => "Column › Page"].
     * Used by the Notices admin so a notice is filed against a real page.
     */
    public static function pageOptions()
    {
        $pages = self::whereNull('deleted_at')
            ->where('link_type', 'page')
            ->with('parent.parent')
            ->ordered()
            ->get();

        return $pages->mapWithKeys(function ($page) {
            $trail = [];
            for ($node = $page->parent; $node; $node = $node->parent) {
                array_unshift($trail, $node->name);
            }
            $trail[] = $page->name;

            return [$page->id => implode(' › ', $trail)];
        });
    }

    /** [page id => layout key], so the admin form can show the right columns. */
    public static function pageLayouts()
    {
        return self::whereNull('deleted_at')
            ->where('link_type', 'page')
            ->pluck('layout', 'id');
    }

    /* ----------------------------- Accessors ----------------------------- */

    /** Where this menu item points, or null when it is just a heading. */
    public function getUrlAttribute()
    {
        switch ($this->link_type) {
            case 'pdf':
                return $this->document_file
                    ? asset('public-notice/documents/' . $this->document_file)
                    : null;
            case 'url':
                return $this->external_link ?: null;
            case 'page':
                return $this->slug
                    ? route('frontend.notice_page', $this->slug)
                    : null;
            default:
                return null;
        }
    }

    /** Blade view for this page, falling back to the plain document list. */
    public function getLayoutViewAttribute()
    {
        $layout = array_key_exists((string) $this->layout, self::LAYOUTS)
            ? $this->layout
            : 'list';

        return 'frontend.public-notice.layouts.' . $layout;
    }

    /** Depth of this node: 1 = column, 2 = menu item, 3 = flyout item. */
    public function getLevelAttribute()
    {
        if (!$this->parent_id) {
            return 1;
        }

        return $this->parent && $this->parent->parent_id ? 3 : 2;
    }
}
