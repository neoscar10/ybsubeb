<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NeedsAssessment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function window()
    {
        return $this->belongsTo(AssessmentWindow::class, 'assessment_window_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function items()
    {
        return $this->hasMany(NeedsItem::class);
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function attachments()
    {
        return $this->hasMany(NeedsAttachment::class);
    }
}
