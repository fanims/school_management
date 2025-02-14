<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ClassRoom;
use App\Models\Teacher;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'class_id',
        'teacher_id',
        'description',
        'total_marks',
        'passing_marks',
        'status'
    ];

    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
