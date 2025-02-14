<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\ClassRoom;

class Attendance extends Model
{
    protected $fillable = [
        'student_id',
        'class_id',
        'date',
        'status',
        'remarks'
    ];

    /**
     * Get the student that owns the attendance record.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the class that owns the attendance record.
     */
    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }
}
