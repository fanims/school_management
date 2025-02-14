<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ClassRoom;
use App\Models\Subject;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'joining_date',
        'qualification',
        'experience',
        'address',
        'phone',
        'email',
        'salary',
        'profile_photo',
        'status'
    ];

    public function classes()
    {
        return $this->hasMany(ClassRoom::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
