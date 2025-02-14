<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fee;
use App\Models\Student;
use App\Models\ClassRoom;

class FeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fees = Fee::with(['student', 'class'])->latest()->paginate(10);
        return view('fees.index', compact('fees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::with('class')->get();
        $classes = ClassRoom::where('status', 'active')->get();
        return view('fees.create', compact('students', 'classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:classes,id',
            'fee_type' => 'required',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:paid,unpaid,partial'
        ]);

        if ($request->status === 'paid') {
            $request->merge([
                'payment_date' => now(),
                'paid_amount' => $request->amount
            ]);
        } elseif ($request->status === 'partial') {
            $request->validate(['paid_amount' => 'required|numeric|min:0|max:' . $request->amount]);
            $request->merge(['payment_date' => now()]);
        }

        Fee::create($request->all());

        return redirect()->route('fees.index')
            ->with('success', 'Fee record created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Fee $fee)
    {
        return view('fees.show', compact('fee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fee $fee)
    {
        $students = Student::where('status', 'active')->get();
        $classes = ClassRoom::where('status', 'active')->get();
        return view('fees.edit', compact('fee', 'students', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fee $fee)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:classes,id',
            'amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0|max:' . $fee->amount,
            'payment_status' => 'required|in:paid,unpaid,partial',
            'payment_date' => 'required|date',
            'remarks' => 'nullable|string'
        ]);

        // Calculate pending amount
        $pendingAmount = $fee->amount - $request->paid_amount;
        
        // Set payment status based on paid amount
        if ($request->paid_amount == 0) {
            $status = 'unpaid';
        } elseif ($request->paid_amount >= $fee->amount) {
            $status = 'paid';
        } else {
            $status = 'partial';
        }

        $fee->update([
            'student_id' => $request->student_id,
            'class_id' => $request->class_id,
            'amount' => $request->amount,
            'paid_amount' => $request->paid_amount,
            'payment_status' => $status,
            'payment_date' => $request->payment_date,
            'remarks' => $request->remarks
        ]);

        return redirect()->route('fees.index')
            ->with('success', 'Fee record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fee $fee)
    {
        $fee->delete();
        return redirect()->route('fees.index')
            ->with('success', 'Fee record deleted successfully');
    }

    /**
     * Display a listing of fees for a specific student.
     */
    public function studentFees(Student $student)
    {
        $fees = $student->fees()->with('class')->latest()->paginate(10);
        return view('fees.student-fees', compact('student', 'fees'));
    }

    /**
     * Record a payment for a fee.
     */
    public function payFee(Request $request, Fee $fee)
    {
        $request->validate([
            'payment_method' => 'required',
            'paid_amount' => 'required|numeric|min:0|max:' . ($fee->amount - $fee->paid_amount),
            'transaction_id' => 'nullable|string',
            'remarks' => 'nullable|string'
        ]);

        $newPaidAmount = $fee->paid_amount + $request->paid_amount;
        $newStatus = $newPaidAmount >= $fee->amount ? 'paid' : 'partial';

        $fee->update([
            'paid_amount' => $newPaidAmount,
            'status' => $newStatus,
            'payment_date' => now(),
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'remarks' => $request->remarks
        ]);

        return redirect()->back()->with('success', 'Payment recorded successfully');
    }
}
