@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <!-- Summary Cards -->
    <div class="row">
        <!-- Students Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalStudents }}</h3>
                    <p>Total Students</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <a href="{{ route('students.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Teachers Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalTeachers }}</h3>
                    <p>Total Teachers</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <a href="{{ route('teachers.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Classes Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalClasses }}</h3>
                    <p>Total Classes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chalkboard"></i>
                </div>
                <a href="{{ route('classes.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Today's Attendance Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $todayAttendance }}%</h3>
                    <p>Today's Attendance</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <a href="{{ route('attendance.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Fee Collection Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Fee Collection (Last 6 Months)</h3>
                </div>
                <div class="card-body">
                    <canvas id="feeCollectionChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <!-- Expense Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Expenses (Last 6 Months)</h3>
                </div>
                <div class="card-body">
                    <canvas id="expenseChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Fee Payments -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Fee Payments</h3>
                </div>
                <div class="card-body table-responsive p-0" style="height: 300px;">
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Student</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentFeePayments as $payment)
                            <tr>
                                <td>{{ $payment->payment_date }}</td>
                                <td>{{ $payment->student->full_name }}</td>
                                <td>{{ number_format($payment->paid_amount, 2) }}</td>
                                <td>
                                    <span class="badge badge-{{ $payment->status === 'paid' ? 'success' : 'warning' }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Expenses -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Expenses</h3>
                </div>
                <div class="card-body table-responsive p-0" style="height: 300px;">
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentExpenses as $expense)
                            <tr>
                                <td>{{ $expense->expense_date }}</td>
                                <td>{{ ucfirst($expense->expense_type) }}</td>
                                <td>{{ number_format($expense->amount, 2) }}</td>
                                <td>{{ ucfirst($expense->payment_method) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('students.create') }}" class="btn btn-app bg-info">
                                <i class="fas fa-user-plus"></i> Add Student
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('fees.create') }}" class="btn btn-app bg-success">
                                <i class="fas fa-money-bill"></i> Record Fee
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('attendance.index') }}" class="btn btn-app bg-warning">
                                <i class="fas fa-calendar-check"></i> Take Attendance
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('expenses.create') }}" class="btn btn-app bg-danger">
                                <i class="fas fa-receipt"></i> Add Expense
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Fee Collection Chart
        var feeCtx = document.getElementById('feeCollectionChart').getContext('2d');
        new Chart(feeCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($feeChartLabels) !!},
                datasets: [{
                    label: 'Fee Collection',
                    data: {!! json_encode($feeChartData) !!},
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Expense Chart
        var expenseCtx = document.getElementById('expenseChart').getContext('2d');
        new Chart(expenseCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($expenseChartLabels) !!},
                datasets: [{
                    label: 'Expenses',
                    data: {!! json_encode($expenseChartData) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgb(255, 99, 132)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@stop
