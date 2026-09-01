<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsMedia extends Model
{
    use HasFactory;

    protected $table = 'news_media';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'category',
        'image',
        'link',
        'pdf_file',
        'sort_order',
        'status',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }
        return asset('news-media-uploads/image/' . $this->image);
    }

    /** Where "Read More" points — an uploaded PDF wins over an external link. */
    public function getReadMoreUrlAttribute()
    {
        if ($this->pdf_file) {
            return asset('news-media-uploads/pdf/' . $this->pdf_file);
        }
        return $this->link ?: null;
    }
}
