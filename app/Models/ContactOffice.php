<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactOffice extends Model
{
    use HasFactory;

    protected $table = 'contact_offices';
    public $timestamps = false;

    const TYPES = [
        'main'   => 'Main Branch Office',
        'branch' => 'Other Branch Office',
    ];

    protected $fillable = [
        'type',
        'city',
        'role',
        'address',
        'contact',
        'email',
        'map_link',
        'tag',
        'sort_order',
        'status',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    /**
     * Where this office's address should point.
     *
     * A map link pasted in the dashboard is used as it stands. Otherwise the
     * address itself is looked up on Google Maps, so every office is clickable
     * without anyone having to find and paste a link for each one.
     */
    public function getMapUrlAttribute(): ?string
    {
        if (trim((string) $this->map_link) !== '') {
            return $this->map_link;
        }

        $query = trim(preg_replace('/\s+/u', ' ', $this->city . ' ' . $this->address));

        return $query === ''
            ? null
            : 'https://www.google.com/maps/search/?api=1&query=' . urlencode($query);
    }
}
