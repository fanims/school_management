<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Students Routes
    Route::resource('students', StudentController::class);
    
    // Teachers Routes
    Route::resource('teachers', TeacherController::class);
    
    // Classes Routes
    Route::resource('classes', ClassRoomController::class);
    
    // Attendance Routes
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');
    Route::get('attendance/{class}', [AttendanceController::class, 'byClass'])->name('attendance.byClass');
    Route::post('attendance/bulk-store', [AttendanceController::class, 'bulkStore'])->name('attendance.bulkStore');
    Route::resource('attendance', AttendanceController::class)->except(['index', 'create', 'store']);
    
    // Fees Routes
    Route::post('fees/{fee}/pay', [FeeController::class, 'payFee'])->name('fees.pay');
    Route::get('fees/student/{student}', [FeeController::class, 'studentFees'])->name('fees.student');
    Route::resource('fees', FeeController::class);
    
    // Expenses Routes
    Route::get('expenses/report', [ExpenseController::class, 'report'])->name('expenses.report');
    Route::resource('expenses', ExpenseController::class);
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
