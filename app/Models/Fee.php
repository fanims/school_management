<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\ClassRoom;

class Fee extends Model
{
    protected $fillable = [
        'student_id',
        'class_id',
        'amount',
        'paid_amount',
        'payment_status',
        'payment_date',
        'remarks'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }
}
