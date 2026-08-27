<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactEnquiry extends Model
{
    use HasFactory;

    protected $table = 'contact_enquiries';
    public $timestamps = false;

    protected $fillable = [
        'first_name',
        'last_name',
        'mobile',
        'email',
        'service',
        'location',
        'comments',
        'is_read',
        'ip_address',
        'created_at',
        'modified_at',
        'modified_by',
        'deleted_at',
        'deleted_by',
    ];

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }
}
