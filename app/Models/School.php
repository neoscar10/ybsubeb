<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'school_type',
        'ownership',
        'status',
        'phone',
        'email',
        'year_established',
        'latitude',
        'longitude',
        'lga',
        'ward',
        'community',
        'address',
        'total_students',
        'total_teachers',
        'total_classrooms',
        'students_male',
        'students_female',
        'teachers_male',
        'teachers_female',
        'has_water',
        'has_toilets',
        'has_electricity',
        'notes',
        'last_updated_by',
        'last_updated_at',
    ];

    protected $casts = [
        'has_water' => 'boolean',
        'has_toilets' => 'boolean',
        'has_electricity' => 'boolean',
        'last_updated_at' => 'datetime',
        'year_established' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function updates()
    {
        return $this->hasMany(SchoolDataUpdate::class);
    }

    public function principal()
    {
        return $this->hasOne(User::class)->where('role', 'principal');
    }

    public function latestUpdate()
    {
        return $this->hasOne(SchoolDataUpdate::class)->latest();
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function needsAssessments()
    {
        return $this->hasMany(NeedsAssessment::class);
    }
}
