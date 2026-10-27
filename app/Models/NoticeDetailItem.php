<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** One document on a notice's own page. */
class NoticeDetailItem extends Model
{
    protected $table = 'notice_detail_items';
    public $timestamps = false;

    protected $fillable = [
        'notice_id',
        'group_title',
        'title',
        'document_file',
        'document_link',
        'is_highlight',
        'sort_order',
        'status',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    public function notice()
    {
        return $this->belongsTo(Notice::class, 'notice_id');
    }

    /** The uploaded file if there is one, otherwise the pasted link. */
    public function getUrlAttribute()
    {
        if ($this->document_file) {
            return asset('public-notice/detail-documents/' . $this->document_file);
        }
        $link = $this->document_link;
        if (!$link) {
            return null;
        }

        // Pages of this site are stored as a path, so they survive a change of domain.
        return str_starts_with($link, '/') ? url($link) : $link;
    }

    /** A link to another page of this site: it opens in the same tab. */
    public function getIsInternalAttribute()
    {
        $link = (string) $this->document_link;

        return !$this->document_file && $link !== ''
            && (str_starts_with($link, '/') || str_starts_with($link, url('/')));
    }

    /** The icon for what the row opens: a document, a page here, or another website. */
    public function getIconAttribute()
    {
        if ($this->document_file) {
            return 'fa-file-pdf-o';
        }

        return $this->is_internal ? 'fa-folder-open-o' : 'fa-external-link';
    }
}
