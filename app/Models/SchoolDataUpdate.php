<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolDataUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'updated_by',
        'update_type',
        'payload',
        'remarks',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
