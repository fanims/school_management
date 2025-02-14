<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = Teacher::latest()->paginate(10);
        return view('teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('teachers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|unique:teachers',
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
            'joining_date' => 'required|date',
            'qualification' => 'required',
            'email' => 'required|email|unique:teachers',
            'phone' => 'required',
            'address' => 'required',
            'salary' => 'required|numeric'
        ]);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('teachers', 'public');
            $request->merge(['profile_photo' => $path]);
        }

        Teacher::create($request->all());

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        $teacher->load(['classes', 'subjects']);
        return view('teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        return view('teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'employee_id' => 'required|unique:teachers,employee_id,' . $teacher->id,
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
            'joining_date' => 'required|date',
            'qualification' => 'required',
            'email' => 'required|email|unique:teachers,email,' . $teacher->id,
            'phone' => 'required',
            'address' => 'required',
            'salary' => 'required|numeric'
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($teacher->profile_photo) {
                Storage::disk('public')->delete($teacher->profile_photo);
            }
            $path = $request->file('profile_photo')->store('teachers', 'public');
            $request->merge(['profile_photo' => $path]);
        }

        $teacher->update($request->all());

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        if ($teacher->profile_photo) {
            Storage::disk('public')->delete($teacher->profile_photo);
        }
        
        $teacher->delete();

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher deleted successfully');
    }
}
