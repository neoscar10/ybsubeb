<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NeedsItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'decided_at' => 'datetime',
        'estimated_cost' => 'decimal:2',
    ];

    public function assessment()
    {
        return $this->belongsTo(NeedsAssessment::class, 'needs_assessment_id');
    }

    public function attachments()
    {
        return $this->hasMany(NeedsAttachment::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'decision_by');
    }
}
