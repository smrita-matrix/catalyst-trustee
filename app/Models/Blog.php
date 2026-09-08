<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blogs';
    public $timestamps = false;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'body', 'image', 'author', 'published_on',
        'sort_order', 'status',
        'created_at', 'created_by', 'modified_at', 'modified_by', 'deleted_at', 'deleted_by',
    ];

    protected $casts = [
        'published_on' => 'date',
    ];

    /** Posts the public should see, newest first. */
    public function scopeLive($query)
    {
        return $query->whereNull('deleted_at')->where('status', 1);
    }

    public function scopeNewestFirst($query)
    {
        return $query->orderByRaw('published_on IS NULL, published_on DESC')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc');
    }

    public function getUrlAttribute(): string
    {
        return route('frontend.blog', $this->slug);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('blog-uploads/' . $this->image) : null;
    }

    /**
     * The few lines shown on the listing: what was written for it, or the
     * opening of the post itself.
     */
    public function getSummaryAttribute(): string
    {
        if (trim((string) $this->excerpt) !== '') {
            return $this->excerpt;
        }

        return Str::limit(trim(strip_tags((string) $this->body)), 180);
    }

    /** Turns a title into the part of the address that names the post. */
    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'post';
        $slug = $base;
        $n    = 1;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . (++$n);
        }

        return $slug;
    }
}
