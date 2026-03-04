<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPromotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'promoted_by',
        'from_class_id',
        'to_class_id',
        'total_promoted',
        'notes',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function promoter()
    {
        return $this->belongsTo(User::class, 'promoted_by');
    }

    public function fromClass()
    {
        return $this->belongsTo(SubebClass::class, 'from_class_id');
    }

    public function toClass()
    {
        return $this->belongsTo(SubebClass::class, 'to_class_id');
    }
}
