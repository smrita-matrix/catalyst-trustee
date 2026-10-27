<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $table = 'notices';
    public $timestamps = false;

    /** Section types (each renders a distinct design block on the frontend). */
    const SECTIONS = [
        'bomsc' => 'Breach of Minimum Security Cover',
        'boc'   => 'Breach Of Covenants',
        'auc'   => 'Auction Notices',
    ];

    protected $fillable = [
        'notice_category_id',
        'parent_notice_id',
        'section',
        'period',
        'category',
        'title',
        'description',
        'notice_date',
        'document_file',
        'document_link',
        'has_detail_page',
        'detail_slug',
        'detail_banner_title',
        'detail_banner_image',
        'sort_order',
        'status',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    /** The Public Notice page this notice is listed on. */
    public function category()
    {
        return $this->belongsTo(NoticeCategory::class, 'notice_category_id');
    }

    /**
     * The same page as category(), under a name the old `category` text
     * column does not shadow: $notice->category reads that column.
     */
    public function noticePage()
    {
        return $this->belongsTo(NoticeCategory::class, 'notice_category_id');
    }

    /** For a page inside another notice's page: that notice. */
    public function parentNotice()
    {
        return $this->belongsTo(self::class, 'parent_notice_id');
    }

    /** Pages inside this notice's page, e.g. "DHFL IBC" inside DHFL. */
    public function subPages()
    {
        return $this->hasMany(self::class, 'parent_notice_id')
            ->whereNull('deleted_at')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc');
    }

    /** The documents on this notice's own page, in the order they are shown. */
    public function detailItems()
    {
        return $this->hasMany(NoticeDetailItem::class, 'notice_id')
            ->whereNull('deleted_at')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc');
    }

    /** The notice's own page on this site, when it has one switched on. */
    public function getDetailUrlAttribute()
    {
        if (!$this->has_detail_page || !$this->detail_slug) {
            return null;
        }

        $page = $this->noticePage;
        if (!$page || !$page->slug) {
            return null;
        }

        if ($this->parent_notice_id) {
            $parent = $this->parentNotice;

            return $parent && $parent->detail_slug
                ? route('frontend.notice_subpage', [$page->slug, $parent->detail_slug, $this->detail_slug])
                : null;
        }

        return route('frontend.notice_detail', [$page->slug, $this->detail_slug]);
    }

    /**
     * Where a card for this notice leads: its own page first, then its
     * document. Only the document opens in a new tab.
     */
    public function getLinkUrlAttribute()
    {
        return $this->detail_url ?: $this->document_url;
    }

    public function getOpensInNewTabAttribute()
    {
        return !$this->detail_url;
    }

    /** Resolve the URL a notice's "View Document" link should point to. */
    public function getDocumentUrlAttribute()
    {
        if ($this->document_file) {
            return asset('public-notice/documents/' . $this->document_file);
        }
        return $this->document_link ?: null;
    }
}
