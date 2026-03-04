<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentWindow extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
    protected $casts = [
        'opens_at' => 'datetime',
        'closes_at' => 'datetime',
    ];

    public function assessments()
    {
        return $this->hasMany(NeedsAssessment::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isOpen()
    {
        return $this->status === 'open' 
            && now()->between($this->opens_at, $this->closes_at);
    }
}
