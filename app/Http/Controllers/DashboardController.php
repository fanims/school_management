<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\ClassRoom;
use App\Models\Attendance;
use App\Models\Fee;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get total counts
        $totalStudents = Student::where('status', 'active')->count();
        $totalTeachers = Teacher::where('status', 'active')->count();
        $totalClasses = ClassRoom::where('status', 'active')->count();

        // Calculate today's attendance percentage
        $today = Carbon::today();
        $totalPresent = Attendance::whereDate('date', $today)
            ->where('status', 'present')
            ->count();
        $totalAttendance = Attendance::whereDate('date', $today)->count();
        $todayAttendance = $totalAttendance > 0 
            ? round(($totalPresent / $totalAttendance) * 100) 
            : 0;

        // Get fee collection data for last 6 months
        $feeData = Fee::select(
            DB::raw('SUM(paid_amount) as total'),
            DB::raw("DATE_FORMAT(payment_date, '%Y-%m') as month")
        )
            ->whereNotNull('payment_date')
            ->where('payment_date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $feeChartLabels = $feeData->pluck('month')->map(function($month) {
            return Carbon::createFromFormat('Y-m', $month)->format('M Y');
        });
        $feeChartData = $feeData->pluck('total');

        // Get expense data for last 6 months
        $expenseData = Expense::select(
            DB::raw('SUM(amount) as total'),
            DB::raw("DATE_FORMAT(expense_date, '%Y-%m') as month")
        )
            ->where('expense_date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $expenseChartLabels = $expenseData->pluck('month')->map(function($month) {
            return Carbon::createFromFormat('Y-m', $month)->format('M Y');
        });
        $expenseChartData = $expenseData->pluck('total');

        // Get recent fee payments
        $recentFeePayments = Fee::with('student')
            ->whereNotNull('payment_date')
            ->orderBy('payment_date', 'desc')
            ->limit(10)
            ->get();

        // Get recent expenses
        $recentExpenses = Expense::orderBy('expense_date', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'totalStudents',
            'totalTeachers',
            'totalClasses',
            'todayAttendance',
            'feeChartLabels',
            'feeChartData',
            'expenseChartLabels',
            'expenseChartData',
            'recentFeePayments',
            'recentExpenses'
        ));
    }
}
