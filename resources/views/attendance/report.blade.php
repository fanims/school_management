@extends('adminlte::page')

@section('title', 'Attendance Report')

@section('content_header')
    <h1>Attendance Report</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Generate Attendance Report</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('attendance.report') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="class_id">Select Class</label>
                            <select name="class_id" id="class_id" class="form-control" required>
                                <option value="">Choose a class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" 
                                   name="start_date" 
                                   id="start_date" 
                                   class="form-control" 
                                   value="{{ $startDate }}"
                                   required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="date" 
                                   name="end_date" 
                                   id="end_date" 
                                   class="form-control" 
                                   value="{{ $endDate }}"
                                   required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="d-block">&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i> Generate Report
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            @if($selectedClass)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Admission No</th>
                                @php
                                    $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
                                @endphp
                                @foreach($period as $date)
                                    <th class="text-center">{{ $date->format('M d') }}</th>
                                @endforeach
                                <th class="text-center">Present</th>
                                <th class="text-center">Absent</th>
                                <th class="text-center">Late</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($selectedClass->students as $student)
                                <tr>
                                    <td>{{ $student->full_name }}</td>
                                    <td>{{ $student->admission_no }}</td>
                                    @php
                                        $presentCount = 0;
                                        $absentCount = 0;
                                        $lateCount = 0;
                                    @endphp
                                    @foreach($period as $date)
                                        @php
                                            $attendance = $attendances[$student->id][$date->format('Y-m-d')] ?? null;
                                            $status = $attendance[0]->status ?? null;
                                            if($status === 'present') $presentCount++;
                                            elseif($status === 'absent') $absentCount++;
                                            elseif($status === 'late') $lateCount++;
                                        @endphp
                                        <td class="text-center">
                                            @if($status === 'present')
                                                <span class="badge badge-success">P</span>
                                            @elseif($status === 'absent')
                                                <span class="badge badge-danger">A</span>
                                            @elseif($status === 'late')
                                                <span class="badge badge-warning">L</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="text-center bg-light"><strong>{{ $presentCount }}</strong></td>
                                    <td class="text-center bg-light"><strong>{{ $absentCount }}</strong></td>
                                    <td class="text-center bg-light"><strong>{{ $lateCount }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($selectedClass->students->isEmpty())
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No students found in this class.
                    </div>
                @endif
            @endif
        </div>
    </div>
@stop

@section('css')
    <style>
        .table th {
            white-space: nowrap;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Validate date range
            $('#end_date').change(function() {
                if($('#start_date').val() > $(this).val()) {
                    alert('End date must be greater than or equal to start date');
                    $(this).val($('#start_date').val());
                }
            });
            
            $('#start_date').change(function() {
                if($(this).val() > $('#end_date').val()) {
                    $('#end_date').val($(this).val());
                }
            });
        });
    </script>
@stop
