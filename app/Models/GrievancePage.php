<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrievancePage extends Model
{
    use HasFactory;

    protected $table = 'grievance_pages';
    public $timestamps = false;

    protected $casts = [
        'contacts' => 'array',
    ];

    protected $fillable = [
        'title',
        'slug',
        'sort_order',
        'status',
        'banner_title',
        'breadcrumb_child',
        'banner_image',
        'heading',
        'intro',
        'body',
        'form_type',
        'link_target',
        'contacts',
        'note',
        'document_file',
        'document_label',
        'external_link',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    /** Which grievance form a page carries, if any. */
    public const FORMS = [
        'none'     => 'No form — just the wording',
        'sebi'     => 'The form for services SEBI regulates',
        'non_sebi' => 'The form for services SEBI does not regulate',
    ];

    /** What the menu entry opens. */
    public const LINK_TARGETS = [
        'page'     => 'A page on the site',
        'document' => 'The document itself, in a new tab',
    ];

    /** How a contact line is written out. */
    public const CONTACT_KINDS = [
        'text'  => 'Plain text',
        'email' => 'Email address',
        'phone' => 'Phone number',
    ];

    /** The pages that should appear, in the order the dashboard sets. */
    public function scopeLive($query)
    {
        return $query->whereNull('deleted_at')
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc');
    }

    /** The document this entry carries, ours or somebody else's. */
    public function getDocumentUrlAttribute(): ?string
    {
        if ($this->document_file) {
            return asset('grievance/documents/' . $this->document_file);
        }

        return $this->external_link ?: null;
    }

    /**
     * Where this entry leads.
     *
     * An entry set to open its document goes straight there; one with no
     * document to open falls back to its page, so it is never a dead link.
     */
    public function getUrlAttribute(): string
    {
        if ($this->link_target === 'document' && $this->document_url) {
            return $this->document_url;
        }

        return route('frontend.grievance_page', $this->slug);
    }

    /** Whether following this entry leaves the site's own pages. */
    public function getOpensDocumentAttribute(): bool
    {
        return $this->link_target === 'document' && (bool) $this->document_url;
    }
}
