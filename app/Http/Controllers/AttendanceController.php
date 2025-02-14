<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassRoom;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = ClassRoom::where('status', 'active')->get();
        return view('attendance.index', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function byClass(ClassRoom $class)
    {
        $date = request('date', now()->format('Y-m-d'));
        $students = $class->students()->with(['attendances' => function($query) use ($date) {
            $query->whereDate('date', $date);
        }])->get();
        
        return view('attendance.by-class', compact('class', 'students', 'date'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:present,absent,late'
        ]);

        foreach ($request->attendances as $attendance) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $attendance['student_id'],
                    'class_id' => $request->class_id,
                    'date' => $request->date
                ],
                [
                    'status' => $attendance['status'],
                    'remarks' => $attendance['remarks'] ?? null,
                    'in_time' => $attendance['in_time'] ?? null,
                    'out_time' => $attendance['out_time'] ?? null
                ]
            );
        }

        return redirect()->back()->with('success', 'Attendance marked successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $attendance = Attendance::with(['student', 'class'])->findOrFail($id);
        return view('attendance.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $attendance = Attendance::with(['student', 'class'])->findOrFail($id);
        return view('attendance.edit', compact('attendance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:present,absent,late',
            'remarks' => 'nullable|string',
            'in_time' => 'nullable|date_format:H:i',
            'out_time' => 'nullable|date_format:H:i|after:in_time'
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->update($request->all());

        return redirect()->route('attendance.byClass', $attendance->class_id)
            ->with('success', 'Attendance updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect()->route('attendance.byClass', $attendance->class_id)
            ->with('success', 'Attendance record deleted successfully');
    }

    /**
     * Display attendance report.
     */
    public function report(Request $request)
    {
        $classes = ClassRoom::where('status', 'active')->get();
        $selectedClass = null;
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $attendances = collect();

        if ($request->has('class_id')) {
            $selectedClass = ClassRoom::findOrFail($request->class_id);
            $attendances = Attendance::with(['student', 'class'])
                ->whereHas('student', function($query) use ($request) {
                    $query->where('class_id', $request->class_id);
                })
                ->whereBetween('date', [$startDate, $endDate])
                ->get()
                ->groupBy(['student_id', 'date']);
        }

        return view('attendance.report', compact('classes', 'selectedClass', 'startDate', 'endDate', 'attendances'));
    }
}
