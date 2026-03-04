<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'class_id',
        'admission_no',
        'first_name',
        'last_name',
        'other_name',
        'gender',
        'date_of_birth',
        'guardian_name',
        'guardian_phone',
        'address',
        'enrollment_date',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'enrollment_date' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function subebClass()
    {
        return $this->belongsTo(SubebClass::class, 'class_id');
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
