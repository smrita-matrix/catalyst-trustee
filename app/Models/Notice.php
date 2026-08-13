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
        'section',
        'period',
        'category',
        'title',
        'description',
        'notice_date',
        'document_file',
        'document_link',
        'sort_order',
        'status',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    /** Resolve the URL a notice's "View Document" link should point to. */
    public function getDocumentUrlAttribute()
    {
        if ($this->document_file) {
            return asset('public-notice/documents/' . $this->document_file);
        }
        return $this->document_link ?: null;
    }
}
