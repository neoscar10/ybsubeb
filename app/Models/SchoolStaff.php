<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolStaff extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'user_id',
        'staff_type',
        'first_name',
        'last_name',
        'other_name',
        'gender',
        'phone',
        'email',
        'date_of_birth',
        'address',
        'qualification',
        'designation',
        'is_active',
        'can_upload_students',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'can_upload_students' => 'boolean',
        'date_of_birth' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->other_name} {$this->last_name}");
    }
}
