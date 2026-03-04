<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class NeedsAttachment extends Model
{
    protected $guarded = ['id'];

    public function item()
    {
        return $this->belongsTo(NeedsItem::class, 'needs_item_id');
    }

    public function assessment()
    {
        return $this->belongsTo(NeedsAssessment::class, 'needs_assessment_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUrlAttribute()
    {
        return Storage::url($this->file_path);
    }
}
