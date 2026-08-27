<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerApplication extends Model
{
    use HasFactory;

    protected $table = 'career_applications';
    public $timestamps = false;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'city',
        'position',
        'intro',
        'resume_file',
        'resume_original_name',
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

    /** Absolute path of the stored CV, used when attaching it to the email. */
    public function getResumePathAttribute()
    {
        return $this->resume_file ? public_path('career-uploads/resumes/' . $this->resume_file) : null;
    }

    public function getResumeUrlAttribute()
    {
        return $this->resume_file ? asset('career-uploads/resumes/' . $this->resume_file) : null;
    }
}
