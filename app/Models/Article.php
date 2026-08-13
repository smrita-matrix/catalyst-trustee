<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';
    public $timestamps = false;

    protected $fillable = [
        'year',
        'title',
        'image',
        'pdf_file',
        'pdf_link',
        'sort_order',
        'status',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    /** URL the download button points to (uploaded PDF wins over external link). */
    public function getPdfUrlAttribute()
    {
        if (!$this->pdf_file) {
            return null;
        }
        if (Str::startsWith($this->pdf_file, ['http://', 'https://'])) {
            return $this->pdf_file;
        }
        return asset('article/pdf/' . $this->pdf_file);
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image; // external URL (e.g. imported images)
        }
        return asset('article/image/' . $this->image);
    }
}
