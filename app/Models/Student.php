<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ClassRoom;
use App\Models\Attendance;
use App\Models\Fee;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'admission_no',
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'email',
        'phone',
        'address',
        'parent_name',
        'parent_phone',
        'class_id',
        'section',
        'profile_photo',
        'status',
        'admission_fee',
        'tuition_fee',
        'total_fee'
    ];

    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
