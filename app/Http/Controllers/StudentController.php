<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\ClassRoom;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with('class')->latest()->paginate(10);
        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes = ClassRoom::where('status', 'active')->get();
        return view('students.create', compact('classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'admission_no' => 'required|unique:students',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date',
            'email' => 'required|email|unique:students',
            'phone' => 'required|string',
            'address' => 'required|string',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string',
            'class_id' => 'required|exists:classes,id',
            'section' => 'nullable|string|max:50',
            'total_fee' => 'required|numeric|min:0',
            'profile_photo' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('students', 'public');
            $request->merge(['profile_photo' => $path]);
        }

        Student::create($request->all());

        return redirect()->route('students.index')
            ->with('success', 'Student registered successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $classes = ClassRoom::where('status', 'active')->get();
        return view('students.edit', compact('student', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'admission_no' => 'required|unique:students,admission_no,' . $student->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date',
            'class_id' => 'required|exists:classes,id',
            'section' => 'nullable|string|max:50',
            'total_fee' => 'required|numeric|min:0',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'phone' => 'required|string',
            'address' => 'required|string',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string',
            'profile_photo' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($student->profile_photo) {
                Storage::disk('public')->delete($student->profile_photo);
            }
            $path = $request->file('profile_photo')->store('students', 'public');
            $request->merge(['profile_photo' => $path]);
        }

        $student->update($request->all());

        return redirect()->route('students.show', $student)
            ->with('success', 'Student updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        if ($student->profile_photo) {
            Storage::disk('public')->delete($student->profile_photo);
        }
        
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully');
    }
}
