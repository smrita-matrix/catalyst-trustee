<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceDocument extends Model
{
    use HasFactory;

    protected $table = 'service_documents';
    public $timestamps = false;

    protected $fillable = [
        'product_id', 'title', 'description', 'document_file', 'document_link',
        'sort_order', 'status',
        'created_at', 'created_by', 'modified_at', 'modified_by', 'deleted_at', 'deleted_by',
    ];

    /** Where this document opens, whether it is ours or somebody else's. */
    public function getDocumentUrlAttribute(): ?string
    {
        if ($this->document_file) {
            return asset('service-uploads/documents/files/' . $this->document_file);
        }

        return $this->document_link ?: null;
    }
}
