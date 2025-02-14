<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = Expense::latest()->paginate(10);
        return view('expenses.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'expense_type' => 'required',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'required',
            'reference_number' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('expenses', 'public');
            $request->merge(['attachment' => $path]);
        }

        Expense::create($request->all());

        return redirect()->route('expenses.index')
            ->with('success', 'Expense recorded successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'expense_type' => 'required',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'required',
            'reference_number' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        if ($request->hasFile('attachment')) {
            if ($expense->attachment) {
                Storage::disk('public')->delete($expense->attachment);
            }
            $path = $request->file('attachment')->store('expenses', 'public');
            $request->merge(['attachment' => $path]);
        }

        $expense->update($request->all());

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        if ($expense->attachment) {
            Storage::disk('public')->delete($expense->attachment);
        }
        
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully');
    }

    public function report(Request $request)
    {
        $query = Expense::query();

        if ($request->filled('from_date')) {
            $query->whereDate('expense_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('expense_date', '<=', $request->to_date);
        }

        if ($request->filled('expense_type')) {
            $query->where('expense_type', $request->expense_type);
        }

        $expenses = $query->latest()->paginate(10);
        $totalAmount = $query->sum('amount');

        return view('expenses.report', compact('expenses', 'totalAmount'));
    }
}
