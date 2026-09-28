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

    /** Where this page lives on the site. */
    public function getUrlAttribute(): string
    {
        return route('frontend.grievance_page', $this->slug);
    }
}
