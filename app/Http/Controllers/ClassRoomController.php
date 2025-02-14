<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassRoom;
use App\Models\Teacher;

class ClassRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = ClassRoom::with('teacher')->latest()->paginate(10);
        return view('classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = Teacher::where('status', 'active')
            ->whereDoesntHave('classes')
            ->get();
        return view('classes.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teachers,id',
            'description' => 'nullable|string'
        ]);

        ClassRoom::create($request->all());

        return redirect()->route('classes.index')
            ->with('success', 'Class created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ClassRoom $class)
    {
        $class->load(['teacher', 'students', 'subjects']);
        return view('classes.show', compact('class'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassRoom $class)
    {
        $teachers = Teacher::where('status', 'active')->get();
        return view('classes.edit', compact('class', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassRoom $class)
    {
        $request->validate([
            'name' => 'required',
            'section' => 'required',
            'capacity' => 'required|integer|min:1',
            'teacher_id' => 'required|exists:teachers,id',
            'description' => 'nullable'
        ]);

        $class->update($request->all());

        return redirect()->route('classes.index')
            ->with('success', 'Class updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassRoom $class)
    {
        $class->delete();

        return redirect()->route('classes.index')
            ->with('success', 'Class deleted successfully');
    }
}
